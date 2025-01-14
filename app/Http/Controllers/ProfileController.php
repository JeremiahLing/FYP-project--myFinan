<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update validated fields
        $user->fill($request->validated());

        // Handle profile picture upload (file-based)
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'], // Validate image file
            ], [
                'profile_picture.max' => 'The profile picture must not exceed 2MB.',
            ]);

            // Delete old profile picture if it exists
            if ($user->profile_picture_path) {
                Storage::disk('public')->delete($user->profile_picture_path);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture_path = $path;
        }

        // Handle cropped image upload (Base64)
        if ($request->filled('cropped_image')) {
            $request->validate([
                'cropped_image' => ['string'], // Ensure it's a string (Base64 format)
            ]);

            // Delete old profile picture if it exists
            if ($user->profile_picture_path) {
                Storage::disk('public')->delete($user->profile_picture_path);
            }

            // Decode and store the Base64 image
            $imageData = $request->input('cropped_image');
            $imageName = 'profile_pictures/' . uniqid() . '.png';

            // Decode Base64 and save
            Storage::disk('public')->put($imageName, base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imageData)));

            $user->profile_picture_path = $imageName;
        }

        // Clear email verification if email changes
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete profile picture if it exists
        if ($user->profile_picture_path) {
            Storage::disk('public')->delete($user->profile_picture_path);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}