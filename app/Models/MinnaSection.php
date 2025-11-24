<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinnaSection extends Model
{
    use HasFactory;

    protected $table = 'minna_sections';

    protected $fillable = [
        'lesson_id',
        'order_index',
        'key',
        'title',
        'content',
        'media_url',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Quan hệ với lesson
     */
    public function lesson()
    {
        return $this->belongsTo(MinnaLesson::class, 'lesson_id');
    }
}

