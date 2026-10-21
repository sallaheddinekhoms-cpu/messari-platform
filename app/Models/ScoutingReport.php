<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoutingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'match_id',
        'scout_user_id',
        // 7 DTR Evaluation Pillars
        'attacking_efficiency',
        'passing_playmaking',
        'dribbling_progression',
        'defensive_performance',
        'physical_performance',
        'tactical_intelligence',
        'match_impact',
        'overall_score',
        // Legacy scores
        'technical_score',
        'tactical_score',
        'physical_score',
        'mental_score',
        'potential_score',
        'strengths',
        'weaknesses',
        'recommendation',
        'visibility'
    ];

    protected $casts = [
        'attacking_efficiency' => 'integer',
        'passing_playmaking' => 'integer',
        'dribbling_progression' => 'integer',
        'defensive_performance' => 'integer',
        'physical_performance' => 'integer',
        'tactical_intelligence' => 'integer',
        'match_impact' => 'integer',
        'overall_score' => 'float',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function scoutUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scout_user_id');
    }

    public function getOverallScoreAttribute(): float
    {
        return round(($this->technical_score + $this->tactical_score + $this->physical_score + $this->mental_score) / 4, 1);
    }
}
