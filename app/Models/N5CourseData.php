<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class N5CourseData extends Model
{
    use HasFactory;

    protected $table = 'n5_course_data';

    protected $fillable = [
        'section_type',
        'section_key',
        'bai',
        'title',
        'content',
        'order',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
