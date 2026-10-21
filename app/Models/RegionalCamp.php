<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegionalCamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'age_category_id',
        'title',
        'location',
        'stadium_id',
        'start_date',
        'end_date',
        'lead_coach',
        'objectives'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    public function callups(): HasMany
    {
        return $this->hasMany(CampCallup::class);
    }
}
