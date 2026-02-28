<?php

namespace App\Services;

use App\Models\Kanji;
use Illuminate\Support\Collection;

class KanjiService
{
    /** Các level hỗ trợ ôn (N5 → N1) */
    public const LEVELS = ['N5', 'N4', 'N3', 'N2', 'N1'];

    /**
     * Số lượng Kanji theo từng level (chỉ levels có dữ liệu)
     */
    public function getCountsByLevel(): Collection
    {
        return Kanji::query()
            ->selectRaw('level, COUNT(*) as count')
            ->whereIn('level', self::LEVELS)
            ->groupBy('level')
            ->orderByRaw("FIELD(level, 'N5', 'N4', 'N3', 'N2', 'N1')")
            ->pluck('count', 'level');
    }

    /**
     * Lấy danh sách Kanji theo level
     */
    public function getByLevel(string $level)
    {
        if (!in_array($level, self::LEVELS, true)) {
            return collect();
        }
        return Kanji::byLevel($level)
            ->orderBy('character')
            ->get(['id', 'character', 'meaning', 'on_reading', 'kun_reading', 'level', 'stroke_count', 'radical', 'examples']);
    }

    /**
     * Lấy kanjis với filter và search
     */
    public function getKanjisWithFilters(?string $level = null, ?string $search = null)
    {
        $query = Kanji::query();

        if ($level) {
            $query->byLevel($level);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('character', 'like', '%' . $search . '%')
                  ->orWhere('meaning', 'like', '%' . $search . '%')
                  ->orWhere('on_reading', 'like', '%' . $search . '%')
                  ->orWhere('kun_reading', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('level')->orderBy('character');
    }
}

