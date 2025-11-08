<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicroExperience extends Model
{
    protected $fillable = [
        'title',
        'emoji',
        'category',
        'description',
        'price_usd',
        'duration_hours',
        'location',
        'image_url',
        'available_packages',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_usd' => 'decimal:2',
            'is_active' => 'boolean',
            'available_packages' => 'array',
        ];
    }
}
