<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RecipeRequest;
use App\Models\Recipe;
use App\Models\Allergy;
use App\Models\Ingredient;

class RecipeController extends Controller
{
    public function index(Request $request) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tab = $request->get('tab', 'recommend');

        if ($tab === 'mylist' && auth()->check()) {
            $recipes = $user->likedRecipes()
                ->orderBy('likes.created_at', 'desc')
                ->paginate(12);
        } elseif ($tab === 'mylist') {
            $recipes = collect();
        } else {
            $recipes = Recipe::query()
                ->latest()
                ->paginate(12);
        }

        return view('recipes.index', compact('recipes','tab'));
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

    public function store(RecipeRequest $request) {
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

    public function update(RecipeRequest $request, $recipe_id) {
        $recipe = Recipe::with([
            'allergies',
            'ingredients',
            'steps',
        ])
        ->findOrFail($recipe_id);

        $path = $request->file('image')->store('images', 'public');

        $recipe->update([
                'image' => $path,
                'name' => $request->name,
                'description' => $request->description,
                'servings' => $request->servings,
                'tips' => $request->tips,
            ]);

        $allergyIds = $request->input('allergy_recipe', []);
        $recipe->allergies()->sync($allergyIds);

        $recipe->ingredients()->detach();

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

        $recipe->steps()->delete();

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
            'user_id' => Auth::user()
        ]);
    }

    public function delete($recipe_id) {

    }
}
