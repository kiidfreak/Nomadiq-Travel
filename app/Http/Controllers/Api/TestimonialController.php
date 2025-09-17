<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    /**
     * Display a listing of published testimonials.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get only published testimonials with their packages
        $testimonials = Testimonial::with('package')
            ->where('is_published', true)
            ->latest()
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $testimonials,
        ]);
    }

    /**
     * Store a newly created testimonial.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:100',
            'package_id' => 'nullable|exists:packages,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the testimonial (unpublished by default)
        $testimonial = Testimonial::create([
            'customer_name' => $request->customer_name,
            'package_id' => $request->package_id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_published' => false, // Require admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial submitted successfully and awaiting approval.',
            'data' => $testimonial,
        ], 201);
    }

    /**
     * Get featured testimonials.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        $featuredTestimonials = Testimonial::with('package')
            ->where('is_published', true)
            ->where('rating', '>=', 4) // Only show 4-5 star ratings
            ->inRandomOrder()
            ->take(3)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $featuredTestimonials,
        ]);
    }
}
