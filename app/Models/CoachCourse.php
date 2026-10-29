<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_type',
        'title',
        'code',
        'description',
        'prerequisites',
        'required_documents',
        'start_date',
        'end_date',
        'registration_deadline',
        'location',
        'max_candidates',
        'fee_amount',
        'is_active',
        'is_registration_open',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'is_active' => 'boolean',
        'is_registration_open' => 'boolean',
        'fee_amount' => 'decimal:2',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(CoachApplication::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(CoachCourseMaterial::class);
    }

    public function getCourseTypeLabelAttribute(): string
    {
        return match ($this->course_type) {
            'goalkeeper' => 'مدربي حراس المرمى (Entraîneur des Gardiens)',
            'faf1' => 'المستوى الأول الفيدرالي (FAF 1)',
            'faf2' => 'المستوى الثاني الفيدرالي (FAF 2)',
            'faf3' => 'المستوى الثالث الفيدرالي (FAF 3)',
            'caf_c' => 'إجازة التدريب الإفريقية (CAF C)',
            'caf_b' => 'إجازة التدريب الإفريقية (CAF B)',
            default => 'تكوين تدريبي',
        };
    }
}
