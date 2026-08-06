<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RecipeStoreRequest;
use App\Http\Requests\RecipeUpdateRequest;
use App\Models\Recipe;
use App\Models\Allergy;
use App\Models\Ingredient;
use App\Models\AllergyCategory;

class RecipeController extends Controller
{
    public function index(Request $request) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tab = $request->get('tab', 'recommend');
        $keyword = $request->get('keyword');
        $selectedAllergies = collect();
        $selectedCategories = collect();
        $allergies = Allergy::all();
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
            $keywords = preg_split('/\s+/', $keyword);
            $message = implode(' ', $keywords) . ' のレシピ一覧';
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
                ->orderBy('likes.created_at', 'desc')
                ->search($keyword, $excludeAllergies, $excludeCategories)
                ->paginate(12);
        } elseif ($tab === 'mylist') {
            $recipes = collect();
        } else {
            $recipes = Recipe::query()
                ->latest()
                ->search($keyword, $excludeAllergies, $excludeCategories)
                ->paginate(12);
        }

        return view('recipes.index', compact('recipes','tab', 'keyword', 'message', 'selectedAllergies', 'selectedCategories', 'excludeAllergies', 'excludeCategories', 'allergies', 'allergyCategories'));
    }

    public function show($recipe_id) {
        $recipe = Recipe::with([
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

        return view('recipes.create', compact('allergies'));
    }

    public function store(RecipeStoreRequest $request) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $path = $request->file('image')->store('images', 'public');

        $recipe = $user->recipes()->create([
            'name' => $request->name,
            'image' => $path,
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
        $allergies = Allergy::all();
        $selectedAllergies = $recipe->allergyIds();

        return view('recipes.edit', compact('recipe', 'allergies', 'selectedAllergies'));
    }

    public function update(RecipeUpdateRequest $request, $recipe_id) {
        $recipe = Recipe::findOrFail($recipe_id);

        DB::transaction(function () use ($request, $recipe) {
            $recipe->update([
                'name' => $request->name,
                'description' => $request->description,
                'servings' => $request->servings,
                'tips' => $request->tips,
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $recipe->update([
                    'image' => $path,
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

        return redirect()->route('profile', [
            'user_id' => Auth::user()->id,
        ]);
    }

    public function delete($recipe_id) {
        $recipe = Recipe::findOrFail($recipe_id);
        $recipe->delete();

        return redirect()->route('profile', [
            'user_id' => Auth::user()->id,
        ]);
    }
}
