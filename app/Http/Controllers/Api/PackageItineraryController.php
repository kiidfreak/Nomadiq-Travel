<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomItinerary;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PackageItineraryController extends Controller
{
    /**
     * Submit a custom itinerary suggestion with email notifications.
     */
    public function submitCustomItinerary(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'package_id' => 'required|exists:packages,id',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'special_requests' => 'nullable|string|max:1000',
                'itinerary' => 'required|array|min:1',
                'itinerary.*.day_number' => 'required|integer|min:1',
                'itinerary.*.title' => 'required|string|max:255',
                'itinerary.*.description' => 'required|string',
                'itinerary.*.accommodation_preference' => 'nullable|string|max:255',
                'itinerary.*.meals_preference' => 'nullable|string|max:255',
                'itinerary.*.activities_preference' => 'nullable|string',
                'itinerary.*.special_notes' => 'nullable|string|max:500',
            ]);

            $package = Package::find($validatedData['package_id']);

            // Check for duplicate day numbers in the request
            $dayNumbers = array_column($validatedData['itinerary'], 'day_number');
            if (count($dayNumbers) !== count(array_unique($dayNumbers))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate day numbers found in the itinerary'
                ], 422);
            }

            $referenceId = 'CIT-' . now()->format('Ymd') . '-' . substr(md5($validatedData['customer_email'] . time()), 0, 6);

            $customItinerary = DB::transaction(function () use ($validatedData, $referenceId) {
                $customItineraries = [];
                
                foreach ($validatedData['itinerary'] as $dayData) {
                    $itinerary = CustomItinerary::create([
                        'package_id' => $validatedData['package_id'],
                        'customer_name' => $validatedData['customer_name'],
                        'customer_email' => $validatedData['customer_email'],
                        'customer_phone' => $validatedData['customer_phone'],
                        'special_requests' => $validatedData['special_requests'],
                        'reference_id' => $referenceId,
                        'submitted_at' => now(),
                        'day_number' => $dayData['day_number'],
                        'title' => $dayData['title'],
                        'description' => $dayData['description'],
                        'accommodation_preference' => $dayData['accommodation_preference'] ?? null,
                        'meals_preference' => $dayData['meals_preference'] ?? null,
                        'activities_preference' => $dayData['activities_preference'] ?? null,
                        'special_notes' => $dayData['special_notes'] ?? null,
                        'status' => 'pending_approval',
                    ]);
                    $customItineraries[] = $itinerary;
                }

                return $customItineraries;
            });

            // Send confirmation email to customer
            try {
                Mail::send('emails.custom-itinerary-submitted', [
                    'customer_name' => $validatedData['customer_name'],
                    'package_name' => $package->title,
                    'reference_id' => $referenceId,
                    'itinerary_days' => count($customItinerary),
                    'special_requests' => $validatedData['special_requests']
                ], function ($message) use ($validatedData, $referenceId) {
                    $message->to($validatedData['customer_email'], $validatedData['customer_name'])
                            ->subject('Custom Itinerary Submitted - ' . $referenceId)
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });

                // Send notification email to admin
                Mail::send('emails.admin-custom-itinerary-notification', [
                    'customer_name' => $validatedData['customer_name'],
                    'customer_email' => $validatedData['customer_email'],
                    'customer_phone' => $validatedData['customer_phone'],
                    'package_name' => $package->title,
                    'reference_id' => $referenceId,
                    'itinerary_days' => count($customItinerary),
                    'special_requests' => $validatedData['special_requests'],
                    'submitted_at' => now()->format('M d, Y \a\t g:i A')
                ], function ($message) use ($referenceId) {
                    $message->to(env('MAIL_ADMIN_EMAIL', 'admin@kanyangasafari.com'))
                            ->subject('New Custom Itinerary Submission - ' . $referenceId)
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });

            } catch (\Exception $emailError) {
                // Log email error but don't fail the request
                \Log::error('Failed to send custom itinerary emails: ' . $emailError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Custom itinerary submitted successfully! You will receive a confirmation email shortly. Our team will review and contact you within 24 hours.',
                'data' => [
                    'reference_id' => $referenceId,
                    'package_name' => $package->title,
                    'customer_name' => $validatedData['customer_name'],
                    'days_count' => count($customItinerary),
                    'status' => 'pending_approval',
                    'submitted_at' => now()->format('M d, Y \a\t g:i A')
                ]
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
                'message' => 'Failed to submit custom itinerary suggestion. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
