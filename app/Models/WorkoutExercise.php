<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_routine_id',
        'customer_id',
        'exercise_name',
        'description',
        'sets',
        'reps',
        'duration_seconds',
        'weight_kg',
        'rest_seconds',
        'notes',
        'order',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
    ];

    /**
     * Get the workout routine that owns the exercise.
     */
    public function workoutRoutine(): BelongsTo
    {
        return $this->belongsTo(WorkoutRoutine::class);
    }

    /**
     * Get the customer that owns the exercise.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope a query by workout routine.
     */
    public function scopeRoutine($query, $routineId)
    {
        return $query->where('workout_routine_id', $routineId);
    }

    /**
     * Scope a query by customer.
     */
    public function scopeCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Get the duration in minutes.
     */
    public function getDurationInMinutesAttribute(): float
    {
        if (!$this->duration_seconds) {
            return 0;
        }
        
        return round($this->duration_seconds / 60, 2);
    }

    /**
     * Get the formatted duration attribute.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return 'N/A';
        }
        
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }
        
        return "{$seconds}s";
    }

    /**
     * Get the formatted weight attribute.
     */
    public function getFormattedWeightAttribute(): string
    {
        if (!$this->weight_kg) {
            return 'N/A';
        }
        
        return $this->weight_kg . ' kg';
    }

    /**
     * Get the formatted rest time attribute.
     */
    public function getFormattedRestTimeAttribute(): string
    {
        if (!$this->rest_seconds) {
            return 'N/A';
        }
        
        $minutes = floor($this->rest_seconds / 60);
        $seconds = $this->rest_seconds % 60;
        
        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }
        
        return "{$seconds}s";
    }
}