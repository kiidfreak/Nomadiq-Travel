<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to get only new inquiries.
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope to get only responded inquiries.
     */
    public function scopeResponded(Builder $query): Builder
    {
        return $query->where('status', 'responded');
    }

    /**
     * Scope to get only closed inquiries.
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', 'closed');
    }

    /**
     * Get the inquiry title.
     */
    public function getInquiryTitleAttribute(): string
    {
        $subject = $this->subject ?: 'General Inquiry';
        return $this->name . ' - ' . $subject;
    }

    /**
     * Get the message excerpt (first 100 characters).
     */
    public function getMessageExcerptAttribute(): string
    {
        if (!$this->message) {
            return '';
        }
        
        $excerpt = substr($this->message, 0, 100);
        return strlen($this->message) > 100 ? $excerpt . '...' : $excerpt;
    }

    /**
     * Get the status color based on the status value.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'warning',
            'responded' => 'info',
            'closed' => 'success',
            default => 'gray',
        };
    }

    /**
     * Get the status badge based on the status value.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'new' => 'New',
            'responded' => 'Responded',
            'closed' => 'Closed',
            default => 'Unknown',
        };
    }

    /**
     * Check if the inquiry is new.
     */
    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    /**
     * Check if the inquiry has been responded to.
     */
    public function isResponded(): bool
    {
        return $this->status === 'responded';
    }

    /**
     * Check if the inquiry is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Mark inquiry as responded.
     */
    public function markAsResponded(): void
    {
        $this->update(['status' => 'responded']);
    }

    /**
     * Mark inquiry as closed.
     */
    public function markAsClosed(): void
    {
        $this->update(['status' => 'closed']);
    }

    /**
     * Mark inquiry as new.
     */
    public function markAsNew(): void
    {
        $this->update(['status' => 'new']);
    }
}
