<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function show($user_id) {
        $user = User::with('profile')
        ->findOrFail($user_id);

        $recipes = $user->recipes()
        ->latest()
        ->paginate(12);

        return view('profile', compact('user', 'recipes'));
    }

    public function edit() {
        return view('auth.profile_edit');
    }
}
