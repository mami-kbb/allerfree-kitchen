<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Http\Requests\RejectRequest;

class AdminRecipeController extends Controller
{
    public function index() {
        $recipes = Recipe::query()
        ->pending()
        ->latest()
        ->paginate(12);

        return view('admin.requests.index', compact('recipes'));
    }

    public function show($recipe_id) {
        $recipe = Recipe::with([
            'allergies',
            'ingredients',
            'steps',
        ])
        ->findOrFail($recipe_id);

        $this->authorize('approve', $recipe);

        $selectedAllergies = $recipe->allergyIds();
        $ingredients = $recipe->ingredients()->incompleteIngredient()->get();

        return view('admin.requests.approve', compact('recipe', 'selectedAllergies', 'ingredients'));
    }

    public function approve($recipe_id) {
        $recipe = Recipe::findOrFail($recipe_id);

        $recipe->update([
                    'status' => 1,
                    'rejection_reason' => null,
                ]);
        
        return redirect()->route('admin.recipe');
    }

    public function reject(RejectRequest $request, $recipe_id) {
        $recipe = Recipe::findOrFail($recipe_id);

        $recipe->update([
                    'status' => 2,
                    'rejection_reason' => $request->rejection_reason,
                ]);
        
        return redirect()->route('admin.recipe');
    }
}
