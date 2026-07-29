<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show($user_id) {
        $user = User::with('profile')
        ->findOrFail($user_id);

        $recipes = $user->recipes()
        ->latest()
        ->paginate(12);

        return view('profiles.show', compact('user', 'recipes'));
    }

    public function edit() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        $allergies = Allergy::all();
        $selectedAllergies = $user->allergyIds();

        return view('profiles.edit', compact('profile', 'allergies', 'selectedAllergies'));
    }

    public function update() {
        
    }
}