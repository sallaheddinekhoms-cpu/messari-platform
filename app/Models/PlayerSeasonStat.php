<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerSeasonStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'season_id',
        'club_team_id',
        'matches_played',
        'starts',
        'minutes_played',
        'goals',
        'assists',
        'clean_sheets',
        'yellow_cards',
        'red_cards',
        'mvp_count'
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function clubTeam(): BelongsTo
    {
        return $this->belongsTo(ClubTeam::class);
    }
}
