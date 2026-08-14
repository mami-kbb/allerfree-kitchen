<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class LikeController extends Controller
{
    public function store(Recipe $recipe) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $recipe->likes()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return back();
    }

    public function destroy(Recipe $recipe) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->likes()
        ->where('recipe_id', $recipe->id)
        ->firstOrFail()
        ->delete();

        return back();
    }
}
