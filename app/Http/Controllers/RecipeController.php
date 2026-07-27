<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        if ($tab === 'mylist' && auth()->check()) {
            $recipes = $user->likedRecipes()
                ->orderBy('likes.created_at', 'desc')
                ->paginate(12);
        } elseif ($tab === 'mylist') {
            $recipes = collect();
        } else {
            $recipes = Recipe::query()
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('index', compact('recipes','tab'));
    }

    public function show($recipe_id) {
        $recipe = Recipe::with([
            'user.profile',
            'allergies',
            'ingredients',
            'steps',
            'comments',
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

        return view('show', compact('recipe', 'isLiked'));
    }

    public function toggle($id) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $like = $user->likes()->where('recipe_id', $id)->first();

        if ($like) {
            $like->delete();
        } else {
            $user->likes()->create(['recipe_id' => $id]);
        }

        return back();
    }
}
