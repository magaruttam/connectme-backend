<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CloudinaryService;
use App\Models\User;

class ProfileController extends Controller
{
    protected $CloudinaryService;

    public function __construct(CloudinaryService $CloudinaryService)
    {
        $this->CloudinaryService = $CloudinaryService;
    }

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

    public function uploadProfileImage(Request $request)
    {
        $result = $this->CloudinaryService->upload(
            $request->file('image')->getRealPath(),
                [
                'folder' => 'users',
                'public_id' => 'avatar_' . auth()->id(),
                'overwrite' => true,
            ]
        );

        $user = auth()->user();
        $user->avatar_url = $result['url'];
        $user->avatar_image_public_id = $result['public_id'];
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Avatar uploaded successfully.',
            'data' => [
                'avatar_url' => $user->avatar_url,
            ],
        ]);
    }
//Update Image
     public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10120', // 5 MB
        ]);

        $user = auth()->user();

        // Delete existing image if it exists
        if ($user->avatar_image_public_id) {
            $this->CloudinaryService->delete($user->avatar_image_public_id);
        }

        // Upload new image
        $result = $this->CloudinaryService->upload(
            $request->file('image')->getRealPath(),
            [
                'folder' => 'users',
                'public_id' => 'avatar_' . auth()->id(),
                'overwrite' => true,
            ]
        );

        // Save Cloudinary data
        $user->avatar_url= $result['url'];
        $user->avatar_image_public_id = $result['public_id'];
        $user->save();

        return response()->json([
            'message' => 'Profile image updated successfully.',
            'data' => [
                'image_url' => $user->avatar_url,
            ],
        ]);
    }
    //Delete Image
    public function deleteImage(Request $request){
          $user = auth()->user();
          // Delete existing image if it exists
        if ($user->avatar_image_public_id) {
            $this->CloudinaryService->delete($user->avatar_image_public_id);
        }
        $user->avatar_url = null;
        $user->avatar_image_public_id = null;
        $user->save();
        
        return response()->json([
            'status' => true,
            'message' => 'Profile image deleted successfully.',
        ]);
    }
}
