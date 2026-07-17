<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // getProfile
    public function getProfile(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Profile retrieved successfully.',
            'data' => $request->user(),
        ]);
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update($validated);
        return response([
            'status'  => true,
            'message' => 'Profile updated successfully.',
            'data'    => $user->fresh(),
        ]);
    }
}
