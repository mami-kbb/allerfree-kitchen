<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use App\Models\Allergy;
use App\Http\Requests\ProfileRequest;

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

    public function update(ProfileRequest $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image')->store('profiles', 'public');
        } else {
            $profileImage = $profile?->profile_image;
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'profile_image' => $profileImage,
                'comment' => $request->comment,
            ]
        );

        $user->update([
            'name' => $request->name,
        ]);

        $allergyIds = $request->input('allergy_user', []);
        $user->allergies()->sync($allergyIds);

        return redirect('/');
    }
}