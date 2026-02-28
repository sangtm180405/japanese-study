<?php

namespace App\Services;

use App\Models\MinnaLesson;
use App\Models\MinnaSection;
use Illuminate\Support\Collection;

class FlashcardService
{
    private const VOCAB_KEYS = ['vocab', 'mau_cau', 'countries', 'proper_nouns', 'cau', 'places', 'rail'];
    private const LOAI_TU = ['danh_tu' => 'Danh từ', 'dong_tu' => 'Động từ', 'tinh_tu' => 'Tính từ'];

    /** Danh sách bài có từ vựng + số thẻ */
    public function getLessonsWithVocabCount(): Collection
    {
        return MinnaSection::where('key', 'tu-vung')
            ->whereNotNull('content')
            ->with('lesson:id,number,title')
            ->get()
            ->map(function (MinnaSection $s) {
                $count = $this->countCards($s->content ?? []);
                return $s->lesson ? ['lesson' => $s->lesson, 'count' => $count] : null;
            })
            ->filter()
            ->values();
    }

    /** Flashcard theo bài */
    public function getFlashcardsByLesson(int $number): array
    {
        $section = MinnaSection::where('key', 'tu-vung')
            ->whereHas('lesson', fn ($q) => $q->where('number', $number))
            ->with('lesson:id,number,title')
            ->first();

        if (!$section?->lesson) {
            return ['lesson' => null, 'cards' => []];
        }

        return [
            'lesson' => $section->lesson,
            'cards' => $this->extractCards($section->content ?? []),
        ];
    }

    private function countCards(array $content): int
    {
        $n = 0;
        foreach (self::VOCAB_KEYS as $key) {
            $items = $content[$key] ?? [];
            if (is_array($items)) {
                foreach ($items as $i) {
                    $f = $i['tu_vung'] ?? $i['jp'] ?? null;
                    if (!empty($f) && !empty($i['nghia'] ?? null)) $n++;
                }
            }
        }
        return $n;
    }

    private function extractCards(array $content): array
    {
        $cards = [];
        foreach (self::VOCAB_KEYS as $key) {
            foreach ($content[$key] ?? [] as $item) {
                $front = $item['tu_vung'] ?? $item['jp'] ?? null;
                $nghia = $item['nghia'] ?? null;
                if (empty($front) || empty($nghia)) continue;

                $parts = [$nghia];
                if (!empty($item['han_tu']) && $item['han_tu'] !== '-') $parts[] = '漢字: ' . $item['han_tu'];
                if (!empty($item['am_han']) && $item['am_han'] !== '-') $parts[] = 'Âm Hán: ' . $item['am_han'];
                if ($lt = $item['loai_tu'] ?? null) $parts[] = self::LOAI_TU[$lt] ?? $lt;
                if (!empty($item['ghi_chu'])) $parts[] = 'Ghi chú: ' . $item['ghi_chu'];

                $cards[] = ['front' => $front, 'back' => implode(' • ', $parts)];
            }
        }
        return $cards;
    }
}
