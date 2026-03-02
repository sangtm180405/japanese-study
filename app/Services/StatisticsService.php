<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function __construct(
        private FlashcardService $flashcardService
    ) {}

    /**
     * Số bài Minna hoàn thành theo từng ngày (N ngày gần nhất)
     * @return array{labels: string[], data: int[]}
     */
    public function getLessonsCompletedByDay(User $user, int $days = 7): array
    {
        $startOfRange = Carbon::today()->subDays($days - 1)->copy()->startOfDay();
        $rows = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $startOfRange)
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->all();

        $labels = [];
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $key = $d->toDateString();
            $labels[] = $d->format('d/m');
            $data[] = (int) ($rows[$key] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Số bài Minna hoàn thành theo từng tuần (N tuần gần nhất).
     * Tuần ISO (T2–Chủ nhật), dùng Carbon để tương thích mọi DB.
     * @return array{labels: string[], data: int[]}
     */
    public function getLessonsCompletedByWeek(User $user, int $weeks = 8): array
    {
        $start = Carbon::today()->subWeeks($weeks)->startOfWeek(Carbon::MONDAY);
        $progresses = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $start)
            ->pluck('completed_at');

        $byWeek = [];
        foreach ($progresses as $completedAt) {
            $dt = $completedAt instanceof Carbon ? $completedAt : Carbon::parse($completedAt);
            $weekStart = $dt->copy()->startOfWeek(Carbon::MONDAY);
            $key = $weekStart->isoWeekYear() * 100 + $weekStart->isoWeek();
            if (!isset($byWeek[$key])) {
                $weekEnd = $weekStart->copy()->addDays(6);
                $byWeek[$key] = [
                    'label' => $weekStart->format('d/m') . '-' . $weekEnd->format('d/m'),
                    'count' => 0,
                ];
            }
            $byWeek[$key]['count']++;
        }

        $labels = [];
        $data = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = Carbon::today()->subWeeks($i)->startOfWeek(Carbon::MONDAY);
            $key = $weekStart->isoWeekYear() * 100 + $weekStart->isoWeek();
            $weekEnd = $weekStart->copy()->addDays(6);
            $labels[] = $byWeek[$key]['label'] ?? ($weekStart->format('d/m') . '-' . $weekEnd->format('d/m'));
            $data[] = $byWeek[$key]['count'] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Tổng số bài đã hoàn thành và ước tính tổng từ vựng (từ các bài đã hoàn thành)
     */
    public function getSummary(User $user): array
    {
        $completedCount = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->count();

        $lessonIds = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->pluck('lesson_id')
            ->all();

        $totalVocab = $this->flashcardService->getTotalVocabCountByLessonIds($lessonIds);

        return [
            'completed_lessons' => $completedCount,
            'total_vocab_estimate' => $totalVocab,
        ];
    }
}
