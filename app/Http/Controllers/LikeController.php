<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store($id) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->likes()->create([
            'recipe_id' => $id,
        ]);

        return back();
    }

    public function destroy($id) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->likes()
        ->where('recipe_id', $id)
        ->firstOrFail()
        ->delete();

        return back();
    }
}
