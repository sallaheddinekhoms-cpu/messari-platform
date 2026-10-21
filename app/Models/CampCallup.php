<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampCallup extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_camp_id',
        'player_id',
        'club_id',
        'invitation_path',
        'attendance_status',
        'technical_notes'
    ];

    public function regionalCamp(): BelongsTo
    {
        return $this->belongsTo(RegionalCamp::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
