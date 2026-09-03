<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RecipeStoreRequest;
use App\Http\Requests\RecipeUpdateRequest;
use App\Models\Recipe;
use App\Models\Allergy;
use App\Models\Ingredient;
use App\Models\AllergyCategory;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class RecipeController extends Controller
{
    public function index(Request $request) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tab = $request->input('tab', 'recommend');
        $keyword = $request->input('keyword');
        $excludeIngredients = $request->input('exclude_ingredients');
        $excludeIngredientsDisplay = $excludeIngredients
        ? preg_replace('/\s+/', '，', trim($excludeIngredients)) : null;
        $selectedAllergies = collect();
        $selectedCategories = collect();
        $allergies = Allergy::where('is_selectable', true)->get();
        $allergyCategories = AllergyCategory::all();

        if ($request->has('allergy_recipe')) {
            $excludeAllergies = array_filter((array) $request->input('allergy_recipe'));
        } else {
            $excludeAllergies = auth()->check() ? $user->allergyIds() :[];
        }

        if ($request->has('allergy_category')) {
            $excludeCategories = array_filter((array) $request->input('allergy_category'));
        } else {
            $excludeCategories = [];
        }

        if ($keyword) {
            $message = "{$keyword} のレシピ一覧";
        } else {
            $message = "レシピ一覧";
        }

        if (!empty($excludeAllergies)) {
            $selectedAllergies = Allergy::whereIn('id', $excludeAllergies)->pluck('name');
        }

        if (!empty($excludeCategories)) {
            $selectedCategories = AllergyCategory::whereIn('id', $excludeCategories)->pluck('category');
        }

        if ($tab === 'mylist' && auth()->check()) {
            $recipes = $user->likedRecipes()
                ->approved()
                ->orderBy('likes.created_at', 'desc')
                ->search($keyword, $excludeIngredients, $excludeAllergies, $excludeCategories)
                ->paginate(12);
        } elseif ($tab === 'mylist') {
            $recipes = collect();
        } else {
            $recipes = Recipe::query()
                ->approved()
                ->latest()
                ->search($keyword, $excludeIngredients, $excludeAllergies, $excludeCategories)
                ->paginate(12);
        }

        return view('recipes.index', compact('recipes','tab', 'keyword', 'excludeIngredientsDisplay', 'message', 'selectedAllergies', 'selectedCategories', 'excludeAllergies', 'excludeCategories', 'allergies', 'allergyCategories'));
    }

    public function show($recipe_id) {
        $recipe = Recipe::approved()
        ->with([
            'user.profile',
            'allergies',
            'ingredients',
            'steps',
            'comments.user.profile',
        ])
        ->withCount([
            'likes',
            'comments',
            ])
        ->findOrFail($recipe_id);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $isLiked = auth()->check() && $user->likes()
        ->where('recipe_id', $recipe_id)->exists();

        return view('recipes.show', compact('recipe', 'isLiked'));
    }

    public function create() {
        $allergies = Allergy::all();
        $ingredients =Ingredient::select('name', 'reading')->get();

        return view('recipes.create', compact('allergies', 'ingredients'));
    }

    public function store(RecipeStoreRequest $request) {

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $imageUrl = null;
        $publicId = null;

        try {
            DB::transaction(function () use ($request, $user, &$imageUrl, &$publicId) {
                //Cloudinaryにアップロードして返ってきたURLをそのまま使う
                $uploaded = $request->file('image')->storeOnCloudinary();
                $imageUrl = $uploaded->getSecurePath();
                $publicId = $uploaded->getPublicId();

                $recipe = $user->recipes()->create([
                    'name' => $request->name,
                    'image' => $imageUrl,
                    'image_public_id' => $publicId,
                    'description' => $request->description,
                    'servings' => $request->servings,
                    'tips' => $request->tips,
                    'status' => 1,
                ]);

                $recipe->allergies()->sync($request->allergy_recipe);

                //数量と紐づけるためにキーと値を取り出す
                foreach ($request->ingredients as $key => $ing_name) {
                    if (empty($ing_name)) {
                        continue;
                    }

                    $ingredient = Ingredient::firstOrCreate([
                        'name' => $ing_name,
                    ]);

                    $recipe->ingredients()->attach($ingredient->id, [
                        'quantity' => $request->quantities[$key] ?? '',
                    ]);
                }

                foreach ($request->steps as $index => $step) {
                    if (empty($step)) {
                        continue;
                    }

                    $recipe->steps()->create([
                        'step_number' => $index + 1,
                        'content' => $step,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            if ($publicId) {
                Cloudinary::destroy($publicId);
            }
            throw $e;
        }

        return redirect()->route('profile', [
            'user_id' => $user->id,
            ]);
    }

    public function edit($recipe_id) {
        $recipe = Recipe::with([
            'allergies',
            'ingredients',
            'steps',
        ])
        ->findOrFail($recipe_id);

        $this->authorize('update', $recipe);

        $allergies = Allergy::all();
        $selectedAllergies = $recipe->allergyIds();
        $ingredients = Ingredient::select('name', 'reading')->get();

        return view('recipes.edit', compact('recipe', 'allergies', 'selectedAllergies', 'ingredients'));
    }

    public function update(RecipeUpdateRequest $request, $recipe_id) {
        $recipe = Recipe::findOrFail($recipe_id);

        $this->authorize('update', $recipe);

        $oldPublicId = $recipe->image_public_id;
        $newPublicId = null;

        try {
            DB::transaction(function () use ($request, $recipe, &$newPublicId) {
                $recipe->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'servings' => $request->servings,
                    'tips' => $request->tips,
                ]);

                if ($request->hasFile('image')) {
                    $uploaded = $request->file('image')->storeOnCloudinary();
                    $newPublicId = $uploaded->getPublicId();

                    $recipe->update([
                        'image' => $uploaded->getSecurePath(),
                        'image_public_id' => $newPublicId,
                    ]);
                }

                $allergyIds = $request->input('allergy_recipe', []);
                $recipe->allergies()->sync($allergyIds);

                //材料をすべて削除
                //マスターの材料は残すからdetachを使用
                $recipe->ingredients()->detach();

                //材料を登録しなおす
                $ingredients = $request->input('ingredients', []);
                $quantities = $request->input('quantities', []);

                foreach ($ingredients as $key => $ing_name) {
                    if (empty($ing_name)) {
                        continue;
                    }

                    $ingredient = Ingredient::firstOrCreate([
                        'name' => $ing_name,
                    ]);

                    $recipe->ingredients()->attach($ingredient->id, [
                        'quantity' => $quantities[$key] ?? null,
                    ]);
                }

                //手順をすべて削除
                $recipe->steps()->delete();

                //手順を登録しなおす
                $steps = $request->input('steps', []);

                foreach ($steps as $index => $step) {
                    if (empty($step)) {
                        continue;
                    }

                    $recipe->steps()->create([
                        'step_number' => $index + 1,
                        'content' => $step,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            if ($newPublicId) {
                Cloudinary::destroy($newPublicId);
            }
            throw $e;
        }

        //保存に成功した場合古い画像を消す
        if ($newPublicId && $oldPublicId) {
            Cloudinary::destroy($oldPublicId);
        }

        return redirect()->route('profile', [
            'user_id' => Auth::user()->id,
        ]);
    }

    public function delete($recipe_id) {
        $recipe = Recipe::findOrFail($recipe_id);

        $this->authorize('delete', $recipe);

        if ($recipe->image_public_id) {
            Cloudinary::destroy($recipe->image_public_id);
        }

        $recipe->delete();

        return redirect()->route('profile', [
            'user_id' => Auth::user()->id,
        ])->with('delete', 'レシピを削除しました');
    }
}
