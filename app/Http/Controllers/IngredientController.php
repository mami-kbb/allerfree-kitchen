<?php

namespace App\Http\Controllers;

use App\Models\AllergyCategory;
use Illuminate\Http\Request;
use App\Models\Ingredient;

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
}
