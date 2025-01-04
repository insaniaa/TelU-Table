<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
        try {
        $user = $request->user();
        $data = $request->all();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                unlink(storage_path('app/public/' . $user->avatar));
            }

            // Store the new avatar and get the file path
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;  // Add the new avatar path to the data array
        }

        // Update the user's profile
        $user->fill($data);

        // Reset email verification if the email has changed
        if ($request->user()->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        } catch(Exception $e) {
            dd($e);
        }

        return Redirect::route('profile.edit')->with('success', 'profile-updated');
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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
