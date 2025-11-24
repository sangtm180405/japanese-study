<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kanji extends Model
{
    use HasFactory;

    protected $fillable = [
        'character',
        'meaning',
        'on_reading',
        'kun_reading',
        'level',
        'stroke_count',
        'radical',
        'examples'
    ];

    // Scope để lọc theo cấp độ
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}
