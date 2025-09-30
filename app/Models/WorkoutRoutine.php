<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'difficulty_level',
        'estimated_duration_minutes',
        'target_muscle_group',
        'trainer_id',
        'member_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the trainer that owns the workout routine.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get the member that this workout routine is assigned to.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'member_id');
    }

    /**
     * Get the workout exercises for the routine.
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    /**
     * Scope a query to only include active routines.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query by difficulty level.
     */
    public function scopeDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    /**
     * Scope a query by target muscle group.
     */
    public function scopeMuscleGroup($query, $group)
    {
        return $query->where('target_muscle_group', $group);
    }

    /**
     * Get the estimated duration in hours.
     */
    public function getDurationInHoursAttribute(): float
    {
        return round($this->estimated_duration_minutes / 60, 2);
    }

    /**
     * Get the formatted duration attribute.
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->estimated_duration_minutes / 60);
        $minutes = $this->estimated_duration_minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    /**
     * Get the exercises count attribute.
     */
    public function getExercisesCountAttribute(): int
    {
        return $this->exercises()->count();
    }
}