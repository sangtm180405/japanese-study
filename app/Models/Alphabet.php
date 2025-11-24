<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alphabet extends Model
{
    use HasFactory;

    protected $fillable = [
        'character',
        'romaji',
        'type',
        'category'
    ];

    // Scope để lọc theo loại
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
