<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Services\MpesaService;
use App\Mail\PaymentConfirmation;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Create a new payment for a booking.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|in:mpesa,bank_transfer,card',
                'transaction_id' => 'nullable|string|max:100',
                'phone_number' => 'required_if:payment_method,mpesa|string|max:20',
            ]);

            $booking = Booking::with(['customer', 'package'])->findOrFail($validatedData['booking_id']);

            // Check if payment amount exceeds balance
            $balance = $booking->balance;
            if ($validatedData['amount'] > $balance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds booking balance. Remaining balance: $' . number_format($balance, 2),
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $validatedData['booking_id'],
                'amount' => $validatedData['amount'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_method'] === 'mpesa' ? 'pending' : 'pending',
                'transaction_id' => $validatedData['transaction_id'] ?? Payment::generateTransactionId(),
            ]);

            // If M-Pesa, initiate STK Push
            if ($validatedData['payment_method'] === 'mpesa' && !empty($validatedData['phone_number'])) {
                try {
                    $mpesaService = new MpesaService();
                    $amount = $validatedData['amount'];
                    $phoneNumber = $validatedData['phone_number'];
                    $accountReference = $booking->booking_reference;
                    $description = "Payment for booking {$booking->booking_reference}";

                    $stkResult = $mpesaService->stkPush($phoneNumber, $amount, $accountReference, $description);

                    if ($stkResult['success']) {
                        // Update payment with checkout request ID
                        $payment->update([
                            'transaction_id' => $stkResult['checkout_request_id'] ?? $payment->transaction_id,
                        ]);

                        Log::info("M-Pesa STK Push initiated successfully", [
                            'payment_id' => $payment->id,
                            'checkout_request_id' => $stkResult['checkout_request_id'],
                            'booking_reference' => $booking->booking_reference,
                        ]);
                    } else {
                        Log::error("M-Pesa STK Push failed", [
                            'payment_id' => $payment->id,
                            'error' => $stkResult['error'] ?? 'Unknown error',
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("M-Pesa service error: " . $e->getMessage(), [
                        'payment_id' => $payment->id,
                    ]);
                    // Payment is still created, but STK push failed
                }
            }

            $payment->load('booking');

            return response()->json([
                'success' => true,
                'message' => 'Payment created successfully',
                'data' => $payment,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment details.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $payment = Payment::with(['booking.customer', 'booking.package'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $payment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }
    }

    /**
     * Get all payments for a booking.
     */
    public function getByBooking(string $bookingId): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            $payments = Payment::where('booking_id', $bookingId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $payments,
                    'total_paid' => $booking->total_paid,
                    'balance' => $booking->balance,
                    'is_fully_paid' => $booking->isFullyPaid(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
            ], 404);
        }
    }

    /**
     * Verify/Update payment status (for webhooks).
     */
    public function verify(Request $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'payment_status' => 'required|in:pending,completed,failed',
                'transaction_id' => 'nullable|string',
            ]);

            $payment = Payment::findOrFail($id);
            $oldStatus = $payment->payment_status;
            $payment->update([
                'payment_status' => $validatedData['payment_status'],
                'transaction_id' => $validatedData['transaction_id'] ?? $payment->transaction_id,
                'paid_at' => $validatedData['payment_status'] === 'completed' ? now() : null,
            ]);

            // Update booking status if fully paid
            $booking = $payment->booking;
            $booking->load(['customer', 'package']);
            $wasFullyPaid = $booking->isFullyPaid();
            $wasPending = $booking->status === 'pending';
            
            if ($wasFullyPaid && $wasPending) {
                $booking->update(['status' => 'confirmed']);
            }

            // Send payment confirmation email if payment was just completed
            if ($validatedData['payment_status'] === 'completed' && $oldStatus !== 'completed') {
                try {
                    Mail::to($booking->customer->email)
                        ->send(new PaymentConfirmation($payment->fresh(), $booking));
                    
                    Log::info('Payment confirmation email sent', [
                        'payment_id' => $payment->id,
                        'booking_reference' => $booking->booking_reference,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                }

                // If booking is now fully paid and confirmed, send confirmation email
                if ($wasFullyPaid && $wasPending) {
                    try {
                        Mail::to($booking->customer->email)
                            ->send(new BookingConfirmation($booking, 'confirmed'));
                        
                        Log::info('Booking confirmation email sent after full payment', [
                            'booking_reference' => $booking->booking_reference,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated',
                'data' => $payment->fresh(['booking']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

