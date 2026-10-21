<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    use HasFactory;

    protected $table = 'media_galleries';

    protected $fillable = [
        'title',
        'type',
        'media_url',
        'thumbnail_url',
        'category',
        'event_date',
        'description',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];
}
