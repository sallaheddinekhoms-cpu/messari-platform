<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'wilaya_id',
        'name_ar',
        'name_fr',
        'short_name',
        'acronym',
        'slug',
        'founded_year',
        'primary_color',
        'secondary_color',
        'logo_path',
        'main_stadium_id',
        'email',
        'phone',
        'website',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($club) {
            if (empty($club->slug)) {
                $club->slug = Str::slug($club->acronym ?: $club->name_fr);
            }
        });
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function stadium(): BelongsTo
    {
        return $this->belongsTo(Stadium::class, 'main_stadium_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(ClubTeam::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(PlayerLicense::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path) {
            if (str_starts_with($this->logo_path, 'http')) {
                return $this->logo_path;
            }
            if (file_exists(public_path($this->logo_path))) {
                return asset($this->logo_path);
            }
            if (file_exists(public_path('storage/' . $this->logo_path))) {
                return asset('storage/' . $this->logo_path);
            }
            if (file_exists(storage_path('app/public/' . $this->logo_path))) {
                return asset('storage/' . $this->logo_path);
            }
        }
        $bg = ltrim($this->primary_color ?? '1e3a12', '#');
        $color = ltrim($this->secondary_color ?? 'ffffff', '#');
        return "https://ui-avatars.com/api/?background={$bg}&color={$color}&name=" . urlencode($this->acronym ?: $this->name_ar) . "&bold=true&format=svg";
    }
}
