<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'theme',
        'tagline',
        'description',
        'highlights',
        'duration_days',
        'price_usd',
        'max_participants',
        'image_url',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_usd' => 'decimal:2',
            'is_active' => 'boolean',
            'highlights' => 'array',
        ];
    }

    /**
     * The destinations that belong to the package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'package_destinations');
    }

    /**
     * The itineraries that belong to the package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(PackageItinerary::class)->orderBy('day_number');
    }

    /**
     * Get the total number of itinerary days
     */
    public function getTotalItineraryDaysAttribute(): int
    {
        return $this->itineraries()->count();
    }

    /**
     * Check if package has complete itinerary (matches duration_days)
     */
    public function hasCompleteItinerary(): bool
    {
        return $this->total_itinerary_days == $this->duration_days;
    }
}
