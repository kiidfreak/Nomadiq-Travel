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
        'caption',
        'safari_date',
        'is_published',
    ];

    protected $casts = [
        'safari_date' => 'date',
        'is_published' => 'boolean',
    ];

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
}
