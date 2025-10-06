<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_type',
        'duration_value',
        'max_visits',
        'includes_trainer',
        'includes_locker',
        'includes_towel',
        'features',
        'image',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'includes_trainer' => 'boolean',
        'includes_locker' => 'boolean',
        'includes_towel' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the subscriptions for the package.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope a query to only include active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include popular packages.
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Get the duration in days.
     */
    public function getDurationInDaysAttribute(): int
    {
        switch ($this->duration_type) {
            case 'days':
                return $this->duration_value;
            case 'weeks':
                return $this->duration_value * 7;
            case 'months':
                return $this->duration_value * 30;
            case 'years':
                return $this->duration_value * 365;
            default:
                return $this->duration_value;
        }
    }

    /**
     * Get the formatted price attribute.
     */
    public function getFormattedPriceAttribute(): string
    {
        return \App\Services\SettingsService::formatCurrency($this->price);
    }

    /**
     * Get the duration description attribute.
     */
    public function getDurationDescriptionAttribute(): string
    {
        return "{$this->duration_value} {$this->duration_type}";
    }
}