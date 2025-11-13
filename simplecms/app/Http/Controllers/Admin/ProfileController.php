<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user's profile
     */
    public function show()
    {
        $user = Auth::user();

        return view('admin.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the profile
     */
    public function edit()
    {
        $user = Auth::user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',

            // Social Links
            'social_twitter' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_github' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_website' => 'nullable|url|max:255',
        ]);

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $user->avatar = null;
            }
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->location = $validated['location'] ?? null;

        // Update social links
        $socialLinks = [];
        if (!empty($validated['social_twitter'])) {
            $socialLinks['twitter'] = $validated['social_twitter'];
        }
        if (!empty($validated['social_facebook'])) {
            $socialLinks['facebook'] = $validated['social_facebook'];
        }
        if (!empty($validated['social_linkedin'])) {
            $socialLinks['linkedin'] = $validated['social_linkedin'];
        }
        if (!empty($validated['social_github'])) {
            $socialLinks['github'] = $validated['social_github'];
        }
        if (!empty($validated['social_instagram'])) {
            $socialLinks['instagram'] = $validated['social_instagram'];
        }
        if (!empty($validated['social_website'])) {
            $socialLinks['website'] = $validated['social_website'];
        }

        $user->social_links = $socialLinks;
        $user->save();

        ActivityLog::log(
            ActivityLog::TYPE_UPDATE,
            "Updated profile information",
            $user
        );

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the form for changing password
     */
    public function editPassword()
    {
        return view('admin.profile.password');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match your current password.'
            ]);
        }

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        ActivityLog::log(
            ActivityLog::TYPE_UPDATE,
            "Changed password",
            $user
        );

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password changed successfully!');
    }
}
