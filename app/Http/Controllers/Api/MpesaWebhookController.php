<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Mail\PaymentConfirmation;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class MpesaWebhookController extends Controller
{
    /**
     * Handle M-Pesa STK Push callback
     */
    public function stkCallback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa STK Callback received', ['data' => $data]);

            // M-Pesa sends the callback in a specific format
            $body = $data['Body'] ?? [];
            $stkCallback = $body['stkCallback'] ?? [];
            
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $callbackMetadata = $stkCallback['CallbackMetadata']['Item'] ?? [];

            // Extract payment details
            $amount = null;
            $mpesaReceiptNumber = null;
            $transactionDate = null;
            $phoneNumber = null;

            foreach ($callbackMetadata as $item) {
                $key = $item['Name'] ?? null;
                $value = $item['Value'] ?? null;

                switch ($key) {
                    case 'Amount':
                        $amount = $value;
                        break;
                    case 'MpesaReceiptNumber':
                        $mpesaReceiptNumber = $value;
                        break;
                    case 'TransactionDate':
                        $transactionDate = $value;
                        break;
                    case 'PhoneNumber':
                        $phoneNumber = $value;
                        break;
                }
            }

            // Find payment by checkout request ID
            $payment = Payment::where('transaction_id', $checkoutRequestId)->first();

            if (!$payment) {
                Log::warning('Payment not found for M-Pesa callback', [
                    'checkout_request_id' => $checkoutRequestId,
                ]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment status
            if ($resultCode == 0) {
                // Payment successful
                $payment->update([
                    'payment_status' => 'completed',
                    'paid_at' => now(),
                    'transaction_id' => $mpesaReceiptNumber ?? $checkoutRequestId,
                ]);

                // Check if booking is fully paid
                $booking = $payment->booking;
                $wasFullyPaid = $booking->isFullyPaid();
                $wasPending = $booking->status === 'pending';
                
                if ($wasFullyPaid && $wasPending) {
                    $booking->update(['status' => 'confirmed']);
                }

                // Send payment confirmation email
                try {
                    $booking->load(['customer', 'package']);
                    Mail::to($booking->customer->email)
                        ->send(new \App\Mail\PaymentConfirmation($payment, $booking));
                    
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

                Log::info('M-Pesa payment completed', [
                    'payment_id' => $payment->id,
                    'booking_reference' => $booking->booking_reference,
                    'mpesa_receipt' => $mpesaReceiptNumber,
                    'booking_fully_paid' => $wasFullyPaid,
                ]);
            } else {
                // Payment failed
                $payment->update([
                    'payment_status' => 'failed',
                ]);

                Log::warning('M-Pesa payment failed', [
                    'payment_id' => $payment->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                ]);
            }

            return response()->json(['message' => 'Callback processed'], 200);
        } catch (\Exception $e) {
            Log::error('M-Pesa callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Error processing callback'], 500);
        }
    }
}

