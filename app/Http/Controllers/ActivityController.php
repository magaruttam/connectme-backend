<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Services\CloudinaryService;
use App\Models\User;

class ActivityController extends Controller
{
    protected $CloudinaryService;

    public function __construct(CloudinaryService $CloudinaryService)
    {
        $this->CloudinaryService = $CloudinaryService;
    }

public function getAllActivities()
{
    $activities = Activity::with(['user', 'category'])
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Activities retrieved successfully.',
        'data' => $activities,
    ]);
}
    public function createActivity(Request $request)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:activity_categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location_name' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        'start_at' => 'required|date',
        'end_at' => 'required|date|after:start_at',
        'max_participants' => 'nullable|integer|min:1',
    ]);

    // Upload activity cover image to Cloudinary
    $result = $this->CloudinaryService->upload(
        $request->file('image')->getRealPath(),
        [
            'folder' => 'activities',
            'public_id' => 'activity_' . uniqid(),
            'overwrite' => false,
        ]
    );

    // Create activity
    $activity = Activity::create([
        'user_id' => auth()->id(),
        'category_id' => $validated['category_id'],
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'cover_image_url' => $result['url'],
        'cover_image_public_id' => $result['public_id'],
        'location_name' => $validated['location_name'],
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
        'start_at' => $request->input('start_at'),
        'end_at' => $request->input('end_at'),
        'max_participants' => $request->input('max_participants'),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Activity created successfully.',
        'data' => $activity,
    ], 201);
}

public function updateActivity(Request $request, Activity $activity)
{
    // Optional: Only allow the owner to update
    if ($activity->user_id !== auth()->id()) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized.',
        ], 403);
    }

    $validated = $request->validate([
        'category_id' => 'required|exists:activity_categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location_name' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        'start_at' => 'required|date',
        'end_at' => 'required|date|after:start_at',
        'max_participants' => 'nullable|integer|min:1',
    ]);

    // Upload new image if provided
    if ($request->hasFile('image')) {

        // Delete old image from Cloudinary
        if ($activity->cover_image_public_id) {
            $this->CloudinaryService->delete($activity->cover_image_public_id);
        }

        $result = $this->CloudinaryService->upload(
            $request->file('image')->getRealPath(),
            [
                'folder' => 'activities',
                'public_id' => 'activity_' . uniqid(),
                'overwrite' => false,
            ]
        );

        $activity->cover_image_url = $result['url'];
        $activity->cover_image_public_id = $result['public_id'];
    }

    $activity->update([
        'category_id' => $validated['category_id'],
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'location_name' => $validated['location_name'],
        'latitude' => $validated['latitude'] ?? null,
        'longitude' => $validated['longitude'] ?? null,
        'start_at' => $validated['start_at'],
        'end_at' => $validated['end_at'],
        'max_participants' => $validated['max_participants'] ?? null,
        'cover_image_url' => $activity->cover_image_url,
        'cover_image_public_id' => $activity->cover_image_public_id,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Activity updated successfully.',
        'data' => $activity->fresh(),
    ]);
}
public function deleteActivity(Activity $activity)
{
    // Optional: Only allow the owner to delete
    if ($activity->user_id !== auth()->id()) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized.',
        ], 403);
    }

    // Delete image from Cloudinary
    if ($activity->cover_image_public_id) {
        $this->CloudinaryService->delete($activity->cover_image_public_id);
    }

    $activity->delete();

    return response()->json([
        'status' => true,
        'message' => 'Activity deleted successfully.',
    ]);
}
}
