<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchLineup extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'club_team_id',
        'player_id',
        'shirt_number',
        'is_starter',
        'is_captain',
        'position_played',
        'subbed_in_minute',
        'subbed_out_minute',
        'minutes_played',
        'rating'
    ];

    protected $casts = [
        'is_starter' => 'boolean',
        'is_captain' => 'boolean',
        'rating' => 'decimal:1',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function clubTeam(): BelongsTo
    {
        return $this->belongsTo(ClubTeam::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
