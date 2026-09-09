<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class AdminRecipeController extends Controller
{
    public function index() {
        $recipes = Recipe::query()
        ->pending()
        ->latest()
        ->paginate(12);

        return view('admin.requests.index', compact('recipes'));
    }
}
