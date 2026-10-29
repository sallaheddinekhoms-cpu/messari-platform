<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachMaterialView extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_course_material_id',
        'coach_application_id',
        'viewed_at',
        'downloaded_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'downloaded_at' => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(CoachCourseMaterial::class, 'coach_course_material_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(CoachApplication::class, 'coach_application_id');
    }
}
