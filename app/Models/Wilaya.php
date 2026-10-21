<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilaya extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name_ar', 'name_fr'];

    public function stadiums(): HasMany
    {
        return $this->hasMany(Stadium::class);
    }

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
