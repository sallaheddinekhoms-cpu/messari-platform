<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachCourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_course_id',
        'title',
        'description',
        'module_number',
        'file_path',
        'video_url',
        'published_at',
        'expires_at',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(CoachCourse::class, 'coach_course_id');
    }

    public function views(): HasMany
    {
        return $this->hasMany(CoachMaterialView::class);
    }

    /**
     * Check if material is currently available for viewing
     */
    public function isAvailable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining days until expiration
     */
    public function getRemainingDaysAttribute(): ?int
    {
        if (! $this->expires_at) {
            return null;
        }

        if (now()->greaterThan($this->expires_at)) {
            return 0;
        }

        return (int) now()->diffInDays($this->expires_at, false) + 1;
    }
}
