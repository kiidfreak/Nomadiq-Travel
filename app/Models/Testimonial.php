<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'package_id',
        'rating',
        'review_text',
        'is_published',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the package associated with this testimonial.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope to get only published testimonials.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get testimonials by rating.
     */
    public function scopeByRating(Builder $query, int $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope to get testimonials with minimum rating.
     */
    public function scopeMinimumRating(Builder $query, int $minRating): Builder
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Get the star rating as a string.
     */
    public function getStarRatingAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get the review excerpt (first 100 characters).
     */
    public function getReviewExcerptAttribute(): string
    {
        if (!$this->review_text) {
            return '';
        }
        
        $excerpt = substr($this->review_text, 0, 100);
        return strlen($this->review_text) > 100 ? $excerpt . '...' : $excerpt;
    }

    /**
     * Get the testimonial title.
     */
    public function getTestimonialTitleAttribute(): string
    {
        $packageName = $this->package ? $this->package->title : 'General';
        return $this->customer_name . ' - ' . $packageName . ' (' . $this->rating . '★)';
    }

    /**
     * Get the rating color based on the rating value.
     */
    public function getRatingColorAttribute(): string
    {
        return match($this->rating) {
            5 => 'success',
            4 => 'info',
            3 => 'warning',
            2 => 'danger',
            1 => 'gray',
            default => 'gray',
        };
    }

    /**
     * Check if the testimonial is highly rated (4 or 5 stars).
     */
    public function isHighlyRated(): bool
    {
        return $this->rating >= 4;
    }

    /**
     * Publish testimonial.
     */
    public function publish(): void
    {
        $this->update(['is_published' => true]);
    }

    /**
     * Unpublish testimonial.
     */
    public function unpublish(): void
    {
        $this->update(['is_published' => false]);
    }
}
