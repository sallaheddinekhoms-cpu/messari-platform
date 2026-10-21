<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stadium extends Model
{
    use HasFactory;

    protected $fillable = [
        'wilaya_id',
        'name',
        'city',
        'surface_type',
        'capacity',
        'google_maps_url'
    ];

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class, 'main_stadium_id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'stadium_id');
    }
}
