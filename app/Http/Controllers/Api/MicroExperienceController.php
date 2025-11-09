<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MicroExperience;
use Illuminate\Http\Request;

class MicroExperienceController extends Controller
{
    /**
     * Display a listing of micro experiences.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $packageId = $request->get('package_id');
        
        $query = MicroExperience::where('is_active', true);
        
        // Filter by package availability if package_id is provided
        if ($packageId) {
            $query->where(function($q) use ($packageId) {
                $q->whereNull('available_packages')
                  ->orWhereJsonContains('available_packages', (int) $packageId);
            });
        }
        
        $experiences = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($experience) {
                // Ensure price_usd is a valid float and fix incorrectly stored prices
                if ($experience->price_usd !== null && $experience->price_usd !== '') {
                    $price = (float) $experience->price_usd;
                    
                    // Fix prices that are clearly too high (likely stored with extra zeros)
                    if ($price > 1000 && $price % 100 === 0 && ($price / 100) < 1000) {
                        $price = $price / 100;
                    } elseif ($price > 1000 && $price % 10 === 0 && ($price / 10) < 1000) {
                        $price = $price / 10;
                    }
                    
                    $experience->price_usd = $price;
                } else {
                    $experience->price_usd = null;
                }
                
                // Ensure image_url is a full URL
                if ($experience->image_url && !filter_var($experience->image_url, FILTER_VALIDATE_URL)) {
                    $appUrl = config('app.url', 'https://nevcompany2.test');
                    if (str_contains($appUrl, 'localhost')) {
                        $appUrl = 'https://nevcompany2.test';
                    }
                    // Handle both storage/micro-experiences/... and micro-experiences/... formats
                    $imagePath = $experience->image_url;
                    if (!str_starts_with($imagePath, 'storage/')) {
                        if (!str_starts_with($imagePath, 'micro-experiences/') && !str_starts_with($imagePath, '/micro-experiences/')) {
                            $imagePath = 'micro-experiences/' . ltrim($imagePath, '/');
                        }
                        $imagePath = 'storage/' . ltrim($imagePath, '/');
                    }
                    $experience->image_url = rtrim($appUrl, '/') . '/' . ltrim($imagePath, '/');
                }
                return $experience;
            });
        
        return response()->json([
            'success' => true,
            'data' => $experiences,
        ]);
    }

    /**
     * Display the specified micro experience.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $experience = MicroExperience::where('is_active', true)
            ->findOrFail($id);
        
        // Ensure price_usd is a valid float and fix incorrectly stored prices
        if ($experience->price_usd !== null && $experience->price_usd !== '') {
            $price = (float) $experience->price_usd;
            
            // Fix prices that are clearly too high
            if ($price > 1000 && $price % 100 === 0 && ($price / 100) < 1000) {
                $price = $price / 100;
            } elseif ($price > 1000 && $price % 10 === 0 && ($price / 10) < 1000) {
                $price = $price / 10;
            }
            
            $experience->price_usd = $price;
        } else {
            $experience->price_usd = null;
        }
        
        // Ensure image_url is a full URL
        if ($experience->image_url && !filter_var($experience->image_url, FILTER_VALIDATE_URL)) {
            $appUrl = config('app.url', 'https://nevcompany2.test');
            if (str_contains($appUrl, 'localhost')) {
                $appUrl = 'https://nevcompany2.test';
            }
            // Handle both storage/micro-experiences/... and micro-experiences/... formats
            $imagePath = $experience->image_url;
            if (!str_starts_with($imagePath, 'storage/')) {
                if (!str_starts_with($imagePath, 'micro-experiences/') && !str_starts_with($imagePath, '/micro-experiences/')) {
                    $imagePath = 'micro-experiences/' . ltrim($imagePath, '/');
                }
                $imagePath = 'storage/' . ltrim($imagePath, '/');
            }
            $experience->image_url = rtrim($appUrl, '/') . '/' . ltrim($imagePath, '/');
        }
        
        return response()->json([
            'success' => true,
            'data' => $experience,
        ]);
    }

    /**
     * Get micro experiences by category.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory(Request $request)
    {
        $category = $request->get('category');
        
        $query = MicroExperience::where('is_active', true)
            ->orderBy('sort_order', 'asc');
        
        if ($category) {
            $query->where('category', $category);
        }
        
        $experiences = $query->get()
            ->map(function($experience) {
                // Ensure price_usd is a valid float and fix incorrectly stored prices
                if ($experience->price_usd !== null && $experience->price_usd !== '') {
                    $price = (float) $experience->price_usd;
                    
                    // Fix prices that are clearly too high
                    if ($price > 1000 && $price % 100 === 0 && ($price / 100) < 1000) {
                        $price = $price / 100;
                    } elseif ($price > 1000 && $price % 10 === 0 && ($price / 10) < 1000) {
                        $price = $price / 10;
                    }
                    
                    $experience->price_usd = $price;
                } else {
                    $experience->price_usd = null;
                }
                
                // Ensure image_url is a full URL
                if ($experience->image_url && !filter_var($experience->image_url, FILTER_VALIDATE_URL)) {
                    $appUrl = config('app.url', 'https://nevcompany2.test');
                    if (str_contains($appUrl, 'localhost')) {
                        $appUrl = 'https://nevcompany2.test';
                    }
                    // Handle both storage/micro-experiences/... and micro-experiences/... formats
                    $imagePath = $experience->image_url;
                    if (!str_starts_with($imagePath, 'storage/')) {
                        if (!str_starts_with($imagePath, 'micro-experiences/') && !str_starts_with($imagePath, '/micro-experiences/')) {
                            $imagePath = 'micro-experiences/' . ltrim($imagePath, '/');
                        }
                        $imagePath = 'storage/' . ltrim($imagePath, '/');
                    }
                    $experience->image_url = rtrim($appUrl, '/') . '/' . ltrim($imagePath, '/');
                }
                return $experience;
            });
        
        return response()->json([
            'success' => true,
            'data' => $experiences,
        ]);
    }
}

