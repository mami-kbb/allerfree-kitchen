<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        ->approved()
        ->latest()
        ->paginate(12);

        return view('profiles.show', compact('user', 'recipes'));
    }

    public function edit() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        $allergies = Allergy::where('is_selectable', true)->get();
        $selectedAllergies = $user->allergyIds();

        return view('profiles.edit', compact('profile', 'allergies', 'selectedAllergies'));
    }

    public function update(ProfileRequest $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();

        $oldImage = $profile?->profile_image;
        $newImage = null;

        //transactionで代入した$newImageを外側でも使用するために"&"を付ける
        try {
            DB::transaction(function () use ($request, $user, $oldImage, &$newImage) {
                if ($request->hasFile('profile_image')) {
                    $newImage = $request->file('profile_image')->store('profiles', 'public');
                }

                //$newImageがあればそれをなければ$oldImageを代入
                $profileImage = $newImage ?? $oldImage;

                //user_idはProfileを探す第一引数。あればupdateなければcreateになる。
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
            });
        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }
            throw $e;
        }

        if ($oldImage && $newImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('profile', ['user_id' => $user->id]);
    }
}