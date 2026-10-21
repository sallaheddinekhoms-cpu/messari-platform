<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyTalent extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'age_category_id',
        'player_id',
        'week_number',
        'headline',
        'highlight_summary',
        'media_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
