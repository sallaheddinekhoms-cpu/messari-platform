<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name_ar',
        'name_fr',
        'min_birth_year',
        'max_birth_year',
        'match_duration_minutes',
        'substitutions_allowed',
        'ball_size'
    ];

    public function clubTeams(): HasMany
    {
        return $this->hasMany(ClubTeam::class);
    }

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(PlayerLicense::class);
    }
}
