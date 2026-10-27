<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'passport_number',
        'first_name_ar',
        'last_name_ar',
        'first_name_fr',
        'last_name_fr',
        'birth_date',
        'birth_place',
        'wilaya_id',
        'nationality',
        'national_id_number',
        'blood_type',
        'dominant_foot',
        'height_cm',
        'weight_kg',
        'primary_position',
        'secondary_position',
        'photo_path',
        'qr_token',
        'status',
        'bio'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($player) {
            if (empty($player->uuid)) {
                $player->uuid = (string) Str::uuid();
            }
            if (empty($player->qr_token)) {
                $player->qr_token = Str::random(40);
            }
            if (empty($player->passport_number)) {
                $year = date('Y');
                $random = strtoupper(Str::random(5));
                $player->passport_number = "MESSARI-{$year}-{$random}";
            }
        });
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(PlayerLicense::class);
    }

    public function currentLicense(): HasOne
    {
        return $this->hasOne(PlayerLicense::class)
            ->whereHas('season', fn($q) => $q->where('is_current', true))
            ->latestOfMany();
    }

    public function careerTimelines(): HasMany
    {
        return $this->hasMany(CareerTimeline::class)->orderBy('season_id', 'desc');
    }

    public function seasonStats(): HasMany
    {
        return $this->hasMany(PlayerSeasonStat::class);
    }

    public function scoutingReports(): HasMany
    {
        return $this->hasMany(ScoutingReport::class);
    }

    public function campCallups(): HasMany
    {
        return $this->hasMany(CampCallup::class);
    }

    public function weeklyTalents(): HasMany
    {
        return $this->hasMany(WeeklyTalent::class);
    }

    public function getFullNameArAttribute(): string
    {
        return "{$this->first_name_ar} {$this->last_name_ar}";
    }

    public function getFullNameFrAttribute(): string
    {
        return "{$this->first_name_fr} {$this->last_name_fr}";
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date ? $this->birth_date->age : 0;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            if (str_starts_with($this->photo_path, 'http') || str_starts_with($this->photo_path, '//')) {
                return $this->photo_path;
            }
            if (str_starts_with($this->photo_path, 'images/')) {
                return asset($this->photo_path);
            }
            return asset('storage/' . $this->photo_path);
        }
        return asset('images/default-avatar.png');
    }
}
