<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CoachApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coach_course_id',
        'application_number',
        'first_name_ar',
        'last_name_ar',
        'first_name_fr',
        'last_name_fr',
        'birth_date',
        'birth_place',
        'national_id_number',
        'wilaya_id',
        'address',
        'phone',
        'email',
        'education_level',
        'sporting_experience',
        'current_club',
        'previous_diplomas',
        'doc_handwritten_request',
        'doc_photo',
        'doc_id_card',
        'doc_player_license',
        'doc_medical_certificate',
        'doc_police_record',
        'doc_residence_certificate',
        'doc_birth_certificate',
        'doc_school_certificate',
        'doc_diplomas',
        'status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(CoachCourse::class, 'coach_course_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function materialViews(): HasMany
    {
        return $this->hasMany(CoachMaterialView::class);
    }

    public function getFullNameArAttribute(): string
    {
        return "{$this->first_name_ar} {$this->last_name_ar}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المعاينة والتدقيق',
            'approved' => 'مقبول ومؤهل للتربص',
            'reserved' => 'تحفظ (ملف غير مكتمل)',
            'rejected' => 'مرفوض',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'approved' => 'emerald',
            'reserved' => 'purple',
            'rejected' => 'rose',
            default => 'slate',
        };
    }
}
