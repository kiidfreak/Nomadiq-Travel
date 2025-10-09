<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                // Customer can be either existing ID or new customer data
                'customer_id' => 'nullable|exists:customers,id',
                'customer' => 'nullable|array',
                'customer.name' => 'required_without:customer_id|string|max:255',
                'customer.email' => 'required_without:customer_id|email|max:255',
                'customer.phone' => 'required_without:customer_id|string|max:20',
                'customer.country' => 'required_without:customer_id|string|max:100',
                
                // Booking details
                'package_id' => 'required|exists:packages,id',
                'start_date' => 'required|date|after:today',
                'number_of_people' => 'required|integer|min:1|max:50',
                'special_requests' => 'nullable|string|max:1000',
            ]);

            // Ensure either customer_id or customer data is provided
            if (empty($validatedData['customer_id']) && !isset($validatedData['customer'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Either customer_id or customer information must be provided'
                ], 422);
            }

            $booking = DB::transaction(function () use ($validatedData) {
                $package = Package::findOrFail($validatedData['package_id']);
                
                // Handle customer creation or retrieval
                if (!empty($validatedData['customer_id'])) {
                    // Use existing customer
                    $customer = Customer::findOrFail($validatedData['customer_id']);
                } else {
                    // Create new customer
                    $customerData = $validatedData['customer'];
                    
                    // Check if customer with this email already exists
                    $existingCustomer = Customer::where('email', $customerData['email'])->first();
                    if ($existingCustomer) {
                        $customer = $existingCustomer;
                    } else {
                        $customer = Customer::create($customerData);
                    }
                }
                
                // Create booking
                $bookingData = [
                    'customer_id' => $customer->id,
                    'package_id' => $validatedData['package_id'],
                    'start_date' => $validatedData['start_date'],
                    'number_of_people' => $validatedData['number_of_people'],
                    'special_requests' => $validatedData['special_requests'] ?? null,
                ];
                
                $booking = new Booking($bookingData);
                $booking->booking_reference = Booking::generateBookingReference();
                $booking->total_amount = $package->price_usd * $validatedData['number_of_people'];
                $booking->status = 'pending';
                $booking->save();

                return $booking;
            });

            $booking->load(['customer', 'package']);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully!',
                'data' => $booking
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $booking = Booking::with(['customer', 'package', 'payments'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'booking' => $booking,
                    'total_paid' => $booking->total_paid,
                    'balance' => $booking->balance,
                    'is_fully_paid' => $booking->isFullyPaid(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Confirm a booking.
     */
    public function confirm(string $id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);

            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending bookings can be confirmed.'
                ], 400);
            }

            $booking->update(['status' => 'confirmed']);
            $booking->load(['customer', 'package']);

            return response()->json([
                'success' => true,
                'message' => 'Booking confirmed successfully!',
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }
}