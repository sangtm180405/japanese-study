<?php

namespace App\Services;

use App\Models\Kanji;

class KanjiService
{
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

