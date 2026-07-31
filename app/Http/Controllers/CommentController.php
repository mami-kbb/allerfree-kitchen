<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function create($id, CommentRequest $request) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->comments()->create([
            'recipe_id' => $id,
            'comment' => $request->comment,
        ]);

        return back();
    }
}
