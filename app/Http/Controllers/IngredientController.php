<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\IngredientUpdateRequest;
use App\Models\Ingredient;
use App\Models\AllergyCategory;


class IngredientController extends Controller
{
    public function index() {
        $categories = Ingredient::query()
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category');

        $allergyCategories = AllergyCategory::all();

        $ingredients = Ingredient::query()
        ->whereNotNull('reading')
        ->whereNotNull('category')
        ->with('allergyCategories')
        ->get();

        $incompleteIngredients = Ingredient::incompleteIngredient()
        ->with('allergyCategories')
        ->get();

        return view('admin.ingredients.index', compact('ingredients', 'incompleteIngredients', 'categories', 'allergyCategories'));
    }

    public function update(IngredientUpdateRequest $request, $ingredient_id) {
        $ingredient = Ingredient::findOrFail($ingredient_id);

        DB::transaction(function () use ($request, $ingredient) {
            $ingredient->update([
                'reading' => $request->reading,
                'category' => $request->category,
            ]);

            $ingredient->allergyCategories()->sync($request->allergy_categories);
        });

        return redirect()->route('ingredients.list');
    }
}
