<?php

namespace App\Services;

use App\Models\MinnaSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FlashcardService
{
    private const VOCAB_KEYS = ['vocab', 'mau_cau', 'countries', 'proper_nouns', 'cau', 'places', 'rail'];
    private const LOAI_TU = ['danh_tu' => 'Danh từ', 'dong_tu' => 'Động từ', 'tinh_tu' => 'Tính từ'];
    private const CACHE_TTL = 600;

    /** Danh sách bài có từ vựng + số thẻ (có cache) */
    public function getLessonsWithVocabCount(): Collection
    {
        return Cache::remember('flashcard:lessons', self::CACHE_TTL, function () {
            return MinnaSection::select('id', 'lesson_id', 'content')
                ->where('key', 'tu-vung')
                ->whereNotNull('content')
                ->with('lesson:id,number,title')
                ->get()
                ->map(function (MinnaSection $s) {
                    $count = $this->countCards($s->content ?? []);
                    return $s->lesson ? ['lesson' => $s->lesson, 'count' => $count] : null;
                })
                ->filter()
                ->values();
        });
    }

    /** Flashcard theo 1 hoặc nhiều bài */
    public function getFlashcardsByLessons(array $numbers, bool $shuffle = false): array
    {
        $numbers = array_values(array_unique(array_filter(array_map('intval', $numbers))));
        if (empty($numbers)) {
            return ['lessons' => [], 'cards' => []];
        }

        $baseCacheKey = 'flashcards:base:' . implode(',', $numbers);

        $base = Cache::remember($baseCacheKey, self::CACHE_TTL, function () use ($numbers) {
            $sections = MinnaSection::where('key', 'tu-vung')
                ->whereHas('lesson', fn ($q) => $q->whereIn('number', $numbers))
                ->with('lesson:id,number,title')
                ->orderBy('lesson_id')
                ->get();

            $lessons = [];
            $allCards = [];
            foreach ($sections as $section) {
                if (!$section->lesson) {
                    continue;
                }
                $cards = $this->extractCards($section->content ?? []);
                foreach ($cards as $c) {
                    $c['lesson_number'] = $section->lesson->number;
                    $allCards[] = $c;
                }
                $lessons[$section->lesson->number] = $section->lesson;
            }

            return [
                'lessons' => array_values($lessons),
                'cards' => $allCards,
            ];
        });

        $cards = $base['cards'];
        if ($shuffle) {
            shuffle($cards);
        }

        return [
            'lessons' => $base['lessons'],
            'cards' => $cards,
        ];
    }

    /** @deprecated Dùng getFlashcardsByLessons([$number]) thay thế */
    public function getFlashcardsByLesson(int $number): array
    {
        $r = $this->getFlashcardsByLessons([$number], false);
        return [
            'lesson' => $r['lessons'][0] ?? null,
            'cards' => $r['cards'],
        ];
    }

    /** Tổng số từ vựng (thẻ) của các bài theo lesson_id - dùng cho thống kê */
    public function getTotalVocabCountByLessonIds(array $lessonIds): int
    {
        if (empty($lessonIds)) {
            return 0;
        }
        $sections = MinnaSection::select('id', 'content')
            ->where('key', 'tu-vung')
            ->whereIn('lesson_id', $lessonIds)
            ->get();
        $total = 0;
        foreach ($sections as $section) {
            $total += $this->countCards($section->content ?? []);
        }
        return $total;
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
