<?php

namespace App\Services;

use App\Models\Alphabet;
use App\Models\Kanji;

class AlphabetService
{
    /**
     * Lấy tất cả alphabets theo type
     */
    public function getAlphabetsByTypes(array $types)
    {
        return Alphabet::whereIn('type', $types)
            ->select('id', 'character', 'romaji', 'type', 'category')
            ->orderBy('type')
            ->orderBy('character')
            ->get();
    }

    /**
     * Chia alphabets theo type
     */
    public function groupAlphabetsByType($alphabets)
    {
        return [
            'hiragana' => $alphabets->where('type', 'hiragana')->values(),
            'katakana' => $alphabets->where('type', 'katakana')->values(),
            'romaji' => $alphabets->where('type', 'romaji')->values(),
        ];
    }

    /**
     * Lấy tất cả kanjis theo levels
     */
    public function getKanjisByLevels(array $levels)
    {
        return Kanji::whereIn('level', $levels)
            ->select('id', 'character', 'meaning', 'on_reading', 'kun_reading', 'level', 'stroke_count', 'radical', 'examples')
            ->orderBy('level')
            ->orderBy('character')
            ->get();
    }

    /**
     * Chia kanjis theo level
     */
    public function groupKanjisByLevel($kanjis)
    {
        return [
            'N5' => $kanjis->where('level', 'N5')->values(),
            'N4' => $kanjis->where('level', 'N4')->values(),
            'N3' => $kanjis->where('level', 'N3')->values(),
        ];
    }

    /**
     * Lấy alphabets với filter và search
     */
    public function getAlphabetsWithFilters(?string $type = null, ?string $search = null)
    {
        $query = Alphabet::query();

        if ($type) {
            $query->byType($type);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('character', 'like', '%' . $search . '%')
                  ->orWhere('romaji', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('type')->orderBy('character');
    }
}

