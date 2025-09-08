<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a listing of the destinations.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get only active destinations
        $destinations = Destination::where('is_active', true)->get();
        
        return response()->json([
            'success' => true,
            'data' => $destinations,
        ]);
    }

    /**
     * Display the specified destination.
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $destination = Destination::with([
                'packages' => function($query) {
                    $query->where('is_active', true);
                }
            ])
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $destination,
        ]);
    }

    /**
     * Get featured destinations.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        $featuredDestinations = Destination::where('is_active', true)
            ->where('is_featured', true)
            ->take(6)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $featuredDestinations,
        ]);
    }
}
