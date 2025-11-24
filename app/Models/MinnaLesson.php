<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinnaLesson extends Model
{
    use HasFactory;

    protected $table = 'minna_lessons';

    protected $fillable = [
        'number',
        'title',
        'description',
    ];

    /**
     * Quan hệ với sections
     */
    public function sections()
    {
        return $this->hasMany(MinnaSection::class, 'lesson_id')->orderBy('order_index');
    }

    /**
     * Lấy section theo key
     */
    public function getSectionByKey($key)
    {
        return $this->sections()->where('key', $key)->first();
    }
}

