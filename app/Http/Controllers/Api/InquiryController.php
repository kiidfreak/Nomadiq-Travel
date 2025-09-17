<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    /**
     * Store a new inquiry from the website.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'package_id' => 'nullable|exists:packages,id',
            'message' => 'required|string',
            'preferred_date' => 'nullable|date|after:today',
            'number_of_people' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the inquiry
        $inquiry = Inquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'package_id' => $request->package_id,
            'message' => $request->message,
            'preferred_date' => $request->preferred_date,
            'number_of_people' => $request->number_of_people,
            'status' => 'new',
        ]);

        // TODO: Send notification email to admin

        return response()->json([
            'success' => true,
            'message' => 'Inquiry submitted successfully. We will contact you soon.',
            'data' => $inquiry,
        ], 201);
    }
}
