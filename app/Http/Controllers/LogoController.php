<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LogoController extends Controller
{
    /**
     * Copy file to public_html for web server access
     */
    private function copyToPublicHtml($logoPath)
    {
        $publicHtmlPath = base_path('public_html/storage/' . $logoPath);
        $publicHtmlDir = dirname($publicHtmlPath);
        
        if (!is_dir($publicHtmlDir)) {
            mkdir($publicHtmlDir, 0755, true);
        }
        
        // Copy file to public_html
        copy(storage_path('app/public/' . $logoPath), $publicHtmlPath);
        chmod($publicHtmlPath, 0644);
    }
    /**
     * Upload profile photo for the authenticated user
     */
    public function upload(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ], [
                'logo.required' => 'Please select a photo to upload.',
                'logo.image' => 'The file must be an image.',
                'logo.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
                'logo.max' => 'The image may not be greater than 2MB.'
            ]);

            $user = Auth::user();

            // Check if file exists
            if (!$request->hasFile('logo')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file was uploaded.',
                    'error_type' => 'no_file'
                ], 400);
            }

            $file = $request->file('logo');

            // Check file size
            if ($file->getSize() > 2048 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'File size is too large. Maximum size is 2MB.',
                    'error_type' => 'file_too_large'
                ], 400);
            }

            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type. Please upload a JPEG, PNG, JPG, GIF, or SVG image.',
                    'error_type' => 'invalid_file_type'
                ], 400);
            }

            // Delete old profile photo if exists
            if ($user->logo && Storage::disk('public')->exists($user->logo)) {
                Storage::disk('public')->delete($user->logo);
            }

            // Store new profile photo
            $logoPath = $file->store('logos', 'public');
            
            // Check if storage was successful
            if (!$logoPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store the image. Please try again.',
                    'error_type' => 'storage_failed'
                ], 500);
            }

            // Update user profile photo
            $user->update(['logo' => $logoPath]);

            // Ensure file is accessible via public URL
            $publicPath = public_path('storage/' . $logoPath);
            $publicDir = dirname($publicPath);
            
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            
            if (!file_exists($publicPath)) {
                copy(storage_path('app/public/' . $logoPath), $publicPath);
            }

            // Copy file to public_html for web server access
            $this->copyToPublicHtml($logoPath);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo uploaded successfully!',
                'logo_url' => Storage::url($logoPath)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all()),
                'error_type' => 'validation_error',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Photo upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ], 500);
        }
    }

    /**
     * Delete profile photo for the authenticated user
     */
    public function delete()
    {
        $user = Auth::user();

        if ($user->logo) {
            // Delete from storage
            Storage::disk('public')->delete($user->logo);
            
            // Delete from public_html with path traversal protection
            $publicHtmlPath = base_path('public_html/storage/' . $user->logo);
            
            // SECURITY: Validate path to prevent path traversal attacks
            $realPath = realpath(dirname($publicHtmlPath));
            $expectedBase = realpath(base_path('public_html/storage'));
            
            // Ensure the file is within the allowed directory
            if ($realPath && $expectedBase && strpos($realPath, $expectedBase) === 0) {
                if (file_exists($publicHtmlPath)) {
                    unlink($publicHtmlPath);
                }
            } else {
                \Log::warning('Attempted path traversal in logo deletion', [
                    'user_id' => $user->id,
                    'logo_path' => $user->logo
                ]);
            }
            
            $user->update(['logo' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile photo deleted successfully!'
        ]);
    }

    /**
     * Get profile photo URL for the authenticated user
     */
    public function getLogo()
    {
        $user = Auth::user();
        
        if ($user->logo) {
            return response()->json([
                'success' => true,
                'logo_url' => Storage::url($user->logo)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No profile photo found'
        ]);
    }
}