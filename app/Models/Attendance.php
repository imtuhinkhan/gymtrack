<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'attendable_type',
        'attendable_id',
        'customer_id',
        'branch_id',
        'trainer_id',
        'date',
        'check_in_time',
        'check_out_time',
        'duration_minutes',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime:H:i:s',
        'check_out_time' => 'datetime:H:i:s',
    ];

    /**
     * Get the attendable model (customer or trainer).
     */
    public function attendable()
    {
        return $this->morphTo();
    }

    /**
     * Get the customer that owns the attendance.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the branch that owns the attendance.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the trainer that owns the attendance.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Scope a query to only include customer attendance.
     */
    public function scopeCustomer($query)
    {
        return $query->where('type', 'customer');
    }

    /**
     * Scope a query to only include trainer attendance.
     */
    public function scopeTrainer($query)
    {
        return $query->where('type', 'trainer');
    }

    /**
     * Scope a query for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if attendance is checked out.
     */
    public function isCheckedOut(): bool
    {
        return !is_null($this->check_out_time);
    }

    /**
     * Get the duration in hours.
     */
    public function getDurationInHoursAttribute(): float
    {
        if (!$this->duration_minutes) {
            return 0;
        }
        
        return round($this->duration_minutes / 60, 2);
    }

    /**
     * Get the formatted duration attribute.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) {
            return 'N/A';
        }
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }
}