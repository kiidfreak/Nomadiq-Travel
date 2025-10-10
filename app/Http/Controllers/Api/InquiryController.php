<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Mail\InquiryReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Create the inquiry
            $inquiry = Inquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'package_id' => $request->package_id,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'new',
            ]);

            // Load the package relationship if it exists
            $inquiry->load('package');

            // Send confirmation email to the customer
            Mail::to($inquiry->email)->send(new InquiryReceived($inquiry));
            
            // Log the inquiry and email sending
            Log::info("New inquiry received from {$inquiry->email}. Confirmation email sent.");

            return response()->json([
                'success' => true,
                'message' => 'Inquiry submitted successfully. We will contact you soon.',
                'data' => $inquiry,
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error processing inquiry: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your inquiry. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
