<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'club_id',
        'season_id',
        'age_category_id',
        'license_number',
        'medical_certificate_path',
        'medical_check_date',
        'medical_status',
        'insurance_policy_number',
        'approval_status',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'medical_check_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

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

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
