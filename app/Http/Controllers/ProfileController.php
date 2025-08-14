<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $user = $request->user();
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old profile photo if exists
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                
                // Store new profile photo
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo = $path;
            }
            
            // Fill other validated data (exclude profile_photo from fillable to avoid conflicts)
            $validatedData = $request->validated();
            unset($validatedData['profile_photo']); // Remove from array to avoid duplicate assignment
            $user->fill($validatedData);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'profile_photo_url' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null
                ]);
            }

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Profile update error: ' . $e->getMessage());
            
            // Return JSON error for AJAX requests
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating profile: ' . $e->getMessage()
                ], 500);
            }
            
            return Redirect::route('profile.edit')->with('error', 'Error updating profile');
        }
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
