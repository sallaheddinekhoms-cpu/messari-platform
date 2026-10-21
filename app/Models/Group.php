<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['competition_id', 'name', 'order'];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function groupTeams(): HasMany
    {
        return $this->hasMany(GroupTeam::class)->orderBy('points', 'desc')->orderBy('goal_difference', 'desc')->orderBy('goals_for', 'desc');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class)->orderBy('round_number');
    }
}
