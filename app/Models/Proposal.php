<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'country',
        'destinations',
        'travel_start_date',
        'travel_end_date',
        'duration',
        'adults',
        'children',
        'children_ages',
        'accommodation_types',
        'room_configuration',
        'activities',
        'special_interests',
        'wildlife_preferences',
        'budget_range',
        'dietary_requirements',
        'mobility_considerations',
        'additional_requests',
        'status',
        'priority',
        'admin_notes',
        'proposal_response',
        'quoted_price',
        'quoted_currency',
        'viewed_at',
        'responded_at',
        'assigned_to',
        'source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'destinations' => 'array',
        'accommodation_types' => 'array',
        'activities' => 'array',
        'special_interests' => 'array',
        'wildlife_preferences' => 'array',
        'travel_start_date' => 'date',
        'travel_end_date' => 'date',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
        'quoted_price' => 'decimal:2',
    ];

    protected $appends = [
        'is_new',
        'days_old',
        'trip_duration_days',
        'total_travelers',
    ];

    // Relationships
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Accessors
    public function getIsNewAttribute(): bool
    {
        return $this->status === 'new' && $this->viewed_at === null;
    }

    public function getDaysOldAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    public function getTripDurationDaysAttribute(): ?int
    {
        if ($this->travel_start_date && $this->travel_end_date) {
            return $this->travel_start_date->diffInDays($this->travel_end_date);
        }
        return null;
    }

    public function getTotalTravelersAttribute(): int
    {
        return $this->adults + $this->children;
    }

    // Get formatted destination names
    public function getDestinationNamesAttribute(): array
    {
        $destinationMap = [
            'kenya' => 'Kenya',
            'tanzania' => 'Tanzania',
            'uganda' => 'Uganda',
            'rwanda' => 'Rwanda',
            'south_africa' => 'South Africa',
            'botswana' => 'Botswana',
        ];

        return collect($this->destinations ?? [])
            ->map(fn($dest) => $destinationMap[$dest] ?? $dest)
            ->toArray();
    }

    // Get budget range label
    public function getBudgetLabelAttribute(): ?string
    {
        $budgetLabels = [
            'budget' => 'Budget ($200-400/night)',
            'mid' => 'Mid-Range ($400-800/night)',
            'luxury' => 'Luxury ($800-1500/night)',
            'ultra' => 'Ultra-Luxury ($1500+/night)',
            'flexible' => 'Flexible',
        ];

        return $budgetLabels[$this->budget_range] ?? null;
    }

    // Scopes
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeUnviewed(Builder $query): Builder
    {
        return $query->whereNull('viewed_at');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', ['new', 'reviewing', 'negotiating']);
    }

    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('priority', 'urgent')
            ->orWhere(function ($q) {
                $q->where('priority', 'high')
                    ->where('created_at', '<=', now()->subDays(2));
            });
    }

    public function scopeExpiringSoon(Builder $query): Builder
    {
        return $query->whereIn('status', ['quoted'])
            ->where('responded_at', '<=', now()->subDays(7))
            ->where('responded_at', '>=', now()->subDays(14));
    }

    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_to', $userId);
    }

    // Methods
    public function markAsViewed(): void
    {
        if (!$this->viewed_at) {
            $this->update(['viewed_at' => now()]);
        }
    }

    public function markAsResponded(): void
    {
        $this->update([
            'responded_at' => now(),
            'status' => 'quoted',
        ]);
    }

    public function assignTo(int $userId): void
    {
        $this->update(['assigned_to' => $userId]);
    }

    public function calculateEstimatedBudget(): ?array
    {
        if (!$this->budget_range || !$this->trip_duration_days) {
            return null;
        }

        $nightlyRates = [
            'budget' => ['min' => 200, 'max' => 400],
            'mid' => ['min' => 400, 'max' => 800],
            'luxury' => ['min' => 800, 'max' => 1500],
            'ultra' => ['min' => 1500, 'max' => 3000],
        ];

        if (!isset($nightlyRates[$this->budget_range])) {
            return null;
        }

        $rates = $nightlyRates[$this->budget_range];
        $travelers = $this->total_travelers;

        return [
            'min' => $rates['min'] * $this->trip_duration_days * $travelers,
            'max' => $rates['max'] * $this->trip_duration_days * $travelers,
            'currency' => 'USD',
        ];
    }
}