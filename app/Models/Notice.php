<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'branch_id',
        'start_date',
        'end_date',
        'is_active',
        'show_on_landing',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'show_on_landing' => 'boolean',
    ];

    /**
     * Get the branch that owns the notice.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created the notice.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active notices.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include notices for landing page.
     */
    public function scopeForLanding($query)
    {
        return $query->where('show_on_landing', true);
    }

    /**
     * Scope a query by notice type.
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query by priority.
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query for current notices.
     */
    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now()->toDateString())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now()->toDateString());
                    });
    }

    /**
     * Check if notice is currently active.
     */
    public function isCurrentlyActive(): bool
    {
        return $this->is_active && 
               $this->start_date <= now()->toDateString() &&
               (!$this->end_date || $this->end_date >= now()->toDateString());
    }

    /**
     * Get the priority color attribute.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get the type icon attribute.
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'general' => 'info',
            'holiday' => 'calendar',
            'maintenance' => 'wrench',
            'promotion' => 'gift',
            'emergency' => 'exclamation-triangle',
            default => 'info',
        };
    }
}