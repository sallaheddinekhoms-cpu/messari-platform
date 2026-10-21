<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'season_id',
        'club_id',
        'age_category_id',
        'matches_count',
        'goals_count',
        'assists_count',
        'minutes_played',
        'yellow_cards',
        'red_cards',
        'achievement_title',
        'notes'
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }
}
