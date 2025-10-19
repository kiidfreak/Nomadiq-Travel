<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomItinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'special_requests',
        'status',
        'admin_notes',
        'reference_id',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'day_number',
        'title',
        'description',
        'accommodation_preference',
        'meals_preference',
        'activities_preference',
        'special_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the package that this custom itinerary belongs to.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the admin user who reviewed this custom itinerary.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get meals as an array.
     */
    public function getMealsArrayAttribute(): array
    {
        if (empty($this->meals_preference)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->meals_preference));
    }

    /**
     * Get day label (e.g., "Day 1", "Day 2").
     */
    public function getDayLabelAttribute(): string
    {
        return "Day {$this->day_number}";
    }

    /**
     * Check if the custom itinerary is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending_approval';
    }

    /**
     * Check if the custom itinerary is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the custom itinerary is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the custom itinerary needs revision.
     */
    public function needsRevision(): bool
    {
        return $this->status === 'needs_revision';
    }

    /**
     * Scope to get custom itineraries by reference ID.
     */
    public function scopeByReference($query, string $referenceId)
    {
        return $query->where('reference_id', $referenceId);
    }

    /**
     * Scope to get custom itineraries by customer.
     */
    public function scopeByCustomer($query, string $customerEmail, ?int $packageId = null)
    {
        $query = $query->where('customer_email', $customerEmail);
        
        if ($packageId) {
            $query->where('package_id', $packageId);
        }
        
        return $query;
    }

    /**
     * Scope to get pending custom itineraries.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending_approval');
    }

    /**
     * Scope to order by day number.
     */
    public function scopeOrderedByDay($query)
    {
        return $query->orderBy('day_number');
    }
}