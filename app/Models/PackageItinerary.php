<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItinerary extends Model
{
    protected $table = 'package_itineraries';

    protected $fillable = [
        'package_id',
        'day_number',
        'title',
        'description',
        'accommodation',
        'meals_included',
        'activities',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get formatted day label
     */
    public function getDayLabelAttribute(): string
    {
        return "Day {$this->day_number}";
    }

    /**
     * Get meals as array
     */
    public function getMealsArrayAttribute(): array
    {
        if (!$this->meals_included) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->meals_included));
    }

    /**
     * Check if specific meal is included
     */
    public function hasMeal(string $meal): bool
    {
        return in_array(ucfirst(strtolower($meal)), $this->meals_array);
    }
}
