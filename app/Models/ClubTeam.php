<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClubTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'season_id',
        'age_category_id',
        'head_coach_name',
        'coach_license_type',
        'team_photo_path',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function groupTeams(): HasMany
    {
        return $this->hasMany(GroupTeam::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'away_team_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return ($this->club?->name_ar ?? 'النادي') . ' (' . ($this->ageCategory?->code ?? '') . ')';
    }
}
