<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'customer_id',
        'package_id',
        'booking_reference',
        'start_date',
        'number_of_people',
        'total_amount',
        'status',
        'special_requests',
        'selected_micro_experiences',
        'addons_total',
    ];

    protected $casts = [
        'start_date' => 'date',
        'total_amount' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'selected_micro_experiences' => 'array',
    ];

    /**
     * Generate a unique booking reference
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BK' . now()->format('Y') . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        } while (static::where('booking_reference', $reference)->exists());
        
        return $reference;
    }

    /**
     * Calculate total amount based on package price, number of people, and add-ons
     */
    public function calculateTotalAmount(): float
    {
        $baseAmount = 0;
        if ($this->package && $this->number_of_people) {
            $baseAmount = $this->package->price_usd * $this->number_of_people;
        }
        
        $addonsAmount = $this->addons_total ?? 0;
        
        return $baseAmount + (float) $addonsAmount;
    }

    /**
     * Get total amount paid for this booking
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()
            ->where('payment_status', 'completed')
            ->sum('amount');
    }

    /**
     * Get remaining balance for this booking
     */
    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->total_paid;
    }

    /**
     * Check if booking is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->balance <= 0;
    }

    /**
     * Check if booking is overpaid
     */
    public function isOverpaid(): bool
    {
        return $this->balance < 0;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
