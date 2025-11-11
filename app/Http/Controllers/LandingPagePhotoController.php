<?php

namespace App\Http\Controllers;

use App\Models\LandingPagePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LandingPagePhotoController extends Controller
{
    /**
     * Get all active photos for landing page
     */
    public function index()
    {
        $photos = LandingPagePhoto::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'photos' => $photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'description' => $photo->description,
                    'image_url' => $photo->image_url,
                    'order' => $photo->order,
                ];
            })
        ]);
    }

    /**
     * Upload a new photo (admin only)
     */
    public function upload(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'order' => 'nullable|integer|min:0',
            ], [
                'photo.required' => 'Please select a photo to upload.',
                'photo.image' => 'The file must be an image.',
                'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif, webp.',
                'photo.max' => 'The photo may not be greater than 5MB.',
            ]);

            if (!$request->hasFile('photo')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No photo was uploaded.',
                ], 400);
            }

            $file = $request->file('photo');

            // Check file size
            if ($file->getSize() > 5120 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo size is too large. Maximum size is 5MB.',
                ], 400);
            }

            // Store photo
            $photoPath = $file->store('landing-photos', 'public');

            if (!$photoPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store the photo. Please try again.',
                ], 500);
            }

            // Create photo record
            $photo = LandingPagePhoto::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image_path' => $photoPath,
                'order' => $request->input('order', 0),
                'is_active' => true,
            ]);

            // Copy to public_html for web server access
            $this->copyToPublicHtml($photoPath);

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully!',
                'photo' => [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'description' => $photo->description,
                    'image_url' => $photo->image_url,
                    'order' => $photo->order,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all()),
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Photo upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a photo (admin only)
     */
    public function delete($id)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        try {
            $photo = LandingPagePhoto::findOrFail($id);

            // Delete from storage
            if ($photo->image_path && Storage::disk('public')->exists($photo->image_path)) {
                Storage::disk('public')->delete($photo->image_path);
            }

            // Delete from public_html
            $publicHtmlPath = base_path('public_html/storage/' . $photo->image_path);
            $realPath = realpath(dirname($publicHtmlPath));
            $expectedBase = realpath(base_path('public_html/storage'));

            if ($realPath && $expectedBase && strpos($realPath, $expectedBase) === 0) {
                if (file_exists($publicHtmlPath)) {
                    unlink($publicHtmlPath);
                }
            }

            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Photo delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update photo order (admin only)
     */
    public function updateOrder(Request $request)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        try {
            $request->validate([
                'photos' => 'required|array',
                'photos.*.id' => 'required|exists:landing_page_photos,id',
                'photos.*.order' => 'required|integer|min:0',
            ]);

            foreach ($request->input('photos') as $photoData) {
                LandingPagePhoto::where('id', $photoData['id'])
                    ->update(['order' => $photoData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Photo order updated successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Photo order update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update photo order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Copy file to public_html for web server access
     */
    private function copyToPublicHtml($photoPath)
    {
        $publicHtmlPath = base_path('public_html/storage/' . $photoPath);
        $publicHtmlDir = dirname($publicHtmlPath);
        
        if (!is_dir($publicHtmlDir)) {
            mkdir($publicHtmlDir, 0755, true);
        }
        
        // Copy file to public_html
        if (file_exists(storage_path('app/public/' . $photoPath))) {
            copy(storage_path('app/public/' . $photoPath), $publicHtmlPath);
            chmod($publicHtmlPath, 0644);
        }
    }
}
