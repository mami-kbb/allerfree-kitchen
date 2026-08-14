<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Recipe;

class CommentController extends Controller
{
    public function create(Recipe $recipe, CommentRequest $request) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $recipe->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return back();
    }
}
