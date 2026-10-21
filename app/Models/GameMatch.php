<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'round_id',
        'home_team_id',
        'away_team_id',
        'stadium_id',
        'match_date',
        'kickoff_time',
        'status',
        'home_score',
        'away_score',
        'home_penalty_score',
        'away_penalty_score',
        'referee_name',
        'assistant1_name',
        'assistant2_name',
        'delegate_name',
        'match_sheet_path',
        'is_validated'
    ];

    protected $casts = [
        'match_date' => 'date',
        'is_validated' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (GameMatch $match) {
            if ($match->round && $match->round->group_id) {
                \App\Services\StandingsService::recalculateGroup($match->round->group_id);
            }
        });

        static::deleted(function (GameMatch $match) {
            if ($match->round && $match->round->group_id) {
                \App\Services\StandingsService::recalculateGroup($match->round->group_id);
            }
        });
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(ClubTeam::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(ClubTeam::class, 'away_team_id');
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class);
    }

    public function lineups(): HasMany
    {
        return $this->hasMany(MatchLineup::class, 'match_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('minute');
    }

    public function scoutingReports(): HasMany
    {
        return $this->hasMany(ScoutingReport::class, 'match_id');
    }
}
