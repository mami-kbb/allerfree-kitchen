<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;
use App\Models\Allergy;
use App\Http\Requests\ProfileRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

        $oldPublicId = $profile?->image_public_id;
        $oldImageUrl = $profile?->profile_image;
        $newPublicId = null;

        //transactionで代入した$newImageを外側でも使用するために"&"を付ける
        try {
            DB::transaction(function () use ($request, $user, $oldPublicId, $oldImageUrl, &$newPublicId) {
                if ($request->hasFile('profile_image')) {
                    $uploaded = $request->file('profile_image')->storeOnCloudinary();
                    $newImageUrl = $uploaded->getSecurePath();
                    $newPublicId = $uploaded->getPublicId();
                }

                //$newImageがあればそれをなければ$oldImageを代入
                $profileImage = $newImageUrl ?? $oldImageUrl;

                $imagePublicId = $newPublicId ?? $oldPublicId;

                //user_idはProfileを探す第一引数。あればupdateなければcreateになる。
                Profile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'profile_image' => $profileImage,
                        'image_public_id' => $imagePublicId,
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
            if ($newPublicId) {
                Cloudinary::destroy($newPublicId);
            }
            throw $e;
        }

        if ($oldPublicId && $newPublicId) {
            Cloudinary::destroy($oldPublicId);
        }

        return redirect()->route('profile', ['user_id' => $user->id]);
    }
}