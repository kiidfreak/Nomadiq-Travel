<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
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
            ->get();
        
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
            ->where('is_featured', true)
            ->take(3)
            ->get();
        
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
        
        // Get results
        $packages = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $packages,
        ]);
    }
}
