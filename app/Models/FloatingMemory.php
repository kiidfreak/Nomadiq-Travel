<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FloatingMemory extends Model
{
    protected $table = 'floating_memories';

    protected $fillable = [
        'destination_id',
        'package_id',
        'image_url',
        'video_url',
        'media_type',
        'caption',
        'traveler_name',
        'story',
        'is_traveler_of_month',
        'safari_date',
        'slot',
        'is_published',
    ];

    protected $casts = [
        'safari_date' => 'date',
        'is_published' => 'boolean',
        'is_traveler_of_month' => 'boolean',
    ];

    protected $appends = ['full_image_url'];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get formatted safari date
     */
    public function getFormattedSafariDateAttribute(): ?string
    {
        return $this->safari_date?->format('F j, Y');
    }

    /**
     * Get memory age in a human-readable format
     */
    public function getMemoryAgeAttribute(): ?string
    {
        if (!$this->safari_date) {
            return null;
        }

        $diffInDays = now()->diffInDays($this->safari_date);
        
        if ($diffInDays < 30) {
            return $diffInDays . ' days ago';
        } elseif ($diffInDays < 365) {
            $months = floor($diffInDays / 30);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        } else {
            $years = floor($diffInDays / 365);
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
        }
    }

    /**
     * Get the memory title based on destination and package
     */
    public function getMemoryTitleAttribute(): string
    {
        $title = '';
        
        if ($this->destination) {
            $title .= $this->destination->name;
        }
        
        if ($this->package) {
            $title .= ($title ? ' - ' : '') . $this->package->title;
        }
        
        return $title ?: 'Safari Memory';
    }

    /**
     * Get the full URL for the image
     */
    public function getFullImageUrlAttribute(): ?string
    {
        $value = $this->attributes['image_url'] ?? null;
        
        if (!$value) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            // Replace localhost with the correct domain if needed
            $appUrl = config('app.url');
            if (str_contains($value, 'localhost') && $appUrl && !str_contains($appUrl, 'localhost')) {
                return str_replace('http://localhost', rtrim($appUrl, '/'), $value);
            }
            return $value;
        }

        // Get the app URL and ensure it doesn't use localhost
        $appUrl = config('app.url', 'https://nevcompany2.test');
        
        // If APP_URL is localhost, use nevcompany2.test instead
        if (str_contains($appUrl, 'localhost')) {
            $appUrl = 'https://nevcompany2.test';
        }
        
        // Remove /storage if already in the path
        $path = $value;
        if (!str_starts_with($path, 'storage/') && !str_starts_with($path, '/storage/')) {
            $path = 'storage/' . ltrim($path, '/');
        } else {
            $path = ltrim($path, '/');
        }
        
        // Return full URL
        return rtrim($appUrl, '/') . '/' . $path;
    }
}
