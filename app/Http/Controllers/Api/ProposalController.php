<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProposalController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'destinations' => 'required|array|min:1',
            'destinations.*' => Rule::in(['kenya', 'tanzania', 'uganda', 'rwanda', 'south_africa', 'botswana']),
            'travelDates.start' => 'nullable|date',
            'travelDates.end' => 'nullable|date|after:travelDates.start',
            'duration' => 'nullable|string',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'children_ages' => 'nullable|string',
            'accommodationType' => 'nullable|array',
            'roomConfiguration' => 'nullable|string',
            'activities' => 'nullable|array',
            'specialInterests' => 'nullable|array',
            'wildlifePreferences' => 'nullable|array',
            'budgetRange' => 'nullable|string',
            'dietaryRequirements' => 'nullable|string',
            'mobilityConsiderations' => 'nullable|string',
            'additionalRequests' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Map frontend keys to database keys
        $proposal = Proposal::create([
            'full_name' => $data['fullName'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'destinations' => $data['destinations'],
            'travel_start_date' => $data['travelDates']['start'] ?? null,
            'travel_end_date' => $data['travelDates']['end'] ?? null,
            'duration' => $data['duration'] ?? null,
            'adults' => $data['adults'] ?? 2,
            'children' => $data['children'] ?? 0,
            'children_ages' => $data['childrenAges'] ?? null,
            'accommodation_types' => $data['accommodationType'] ?? [],
            'room_configuration' => $data['roomConfiguration'] ?? null,
            'activities' => $data['activities'] ?? [],
            'special_interests' => $data['specialInterests'] ?? [],
            'wildlife_preferences' => $data['wildlifePreferences'] ?? [],
            'budget_range' => $data['budgetRange'] ?? null,
            'dietary_requirements' => $data['dietaryRequirements'] ?? null,
            'mobility_considerations' => $data['mobilityConsiderations'] ?? null,
            'additional_requests' => $data['additionalRequests'] ?? null,
            'status' => 'new',
            'priority' => 'medium',
            'source' => 'website',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Optional: Send notification email to admins
        // Notification::route('mail', config('mail.admin_email'))
        //     ->notify(new NewProposalNotification($proposal));

        // Optional: Send confirmation email to client
        // Mail::to($proposal->email)->send(new ProposalReceivedMail($proposal));

        return response()->json([
            'success' => true,
            'message' => 'Proposal submitted successfully',
            'proposal_id' => $proposal->id
        ], 201);
    }
}