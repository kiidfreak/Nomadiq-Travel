<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Transform package image URL to full URL or fallback
     */
    private function transformImageUrl($package)
    {
        // If already a full URL (Unsplash, etc.), return as is
        if ($package->image_url && filter_var($package->image_url, FILTER_VALIDATE_URL)) {
            return $package;
        }

        // If no image_url, set a fallback
        if (empty($package->image_url)) {
            $package->image_url = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800';
            return $package;
        }

        // Use APP_URL from environment (Railway sets this automatically)
        $appUrl = env('APP_URL', config('app.url', 'https://nomadiq-travel-production.up.railway.app'));
        
        // Handle both storage/packages/... and packages/... formats
        $imagePath = $package->image_url;
        if (!str_starts_with($imagePath, 'storage/')) {
            if (!str_starts_with($imagePath, 'packages/') && !str_starts_with($imagePath, '/packages/')) {
                $imagePath = 'packages/' . ltrim($imagePath, '/');
            }
            $imagePath = 'storage/' . ltrim($imagePath, '/');
        }

        // Extract just the filename part for checking
        $storageCheckPath = str_replace('storage/', '', $imagePath);
        
        // Check if file actually exists in storage
        if (!Storage::disk('public')->exists($storageCheckPath)) {
            // File doesn't exist, use Unsplash fallback based on package ID
            $fallbacks = [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800',
                'https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?w=800',
            ];
            $package->image_url = $fallbacks[$package->id % count($fallbacks)];
        } else {
            // File exists, use the full URL
            $package->image_url = rtrim($appUrl, '/') . '/' . ltrim($imagePath, '/');
        }
        
        return $package;
    }

    /**
     * Display a listing of the packages.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get only active packages with their destinations
        $packages = Package::with(['destinations' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function($package) {
                return $this->transformImageUrl($package);
            });
        
        return response()->json([
            'success' => true,
            'data' => $packages,
        ]);
    }

    /**
     * Display the specified package.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $package = Package::with([
                'destinations' => function($query) {
                    $query->where('is_active', true);
                },
                'itineraries' => function($query) {
                    $query->orderBy('day_number');
                }
            ])
            ->findOrFail($id);
        
        $package = $this->transformImageUrl($package);
        
        return response()->json([
            'success' => true,
            'data' => $package,
        ]);
    }

    /**
     * Get featured packages.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        $featuredPackages = Package::with(['destinations' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->take(3)
            ->get()
            ->map(function($package) {
                return $this->transformImageUrl($package);
            });
        
        return response()->json([
            'success' => true,
            'data' => $featuredPackages,
        ]);
    }

    /**
     * Search packages.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = Package::query()->with(['destinations']);
        
        // Filter by destination
        if ($request->has('destination_id')) {
            $query->whereHas('destinations', function($q) use ($request) {
                $q->where('destinations.id', $request->destination_id);
            });
        }
        
        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Filter by duration
        if ($request->has('duration')) {
            $query->where('duration', '=', $request->duration);
        }
        
        // Only show active packages
        $query->where('is_active', true);
        
        // Get results and transform image URLs
        $packages = $query->get()->map(function($package) {
            return $this->transformImageUrl($package);
        });
        
        return response()->json([
            'success' => true,
            'data' => $packages,
        ]);
    }
}
