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
}
