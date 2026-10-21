<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'club_team_id',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
        'penalty_deductions',
        'rank',
        'form'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function clubTeam(): BelongsTo
    {
        return $this->belongsTo(ClubTeam::class);
    }
}
