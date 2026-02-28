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
        $start = Carbon::today()->subDays($days - 1);
        $rows = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $start->startOfDay())
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
     * Số bài Minna hoàn thành theo từng tuần (N tuần gần nhất)
     * Tuần tính từ T2 đến Chủ nhật.
     * @return array{labels: string[], data: int[]}
     */
    public function getLessonsCompletedByWeek(User $user, int $weeks = 8): array
    {
        $start = Carbon::today()->subWeeks($weeks)->startOfWeek(Carbon::MONDAY);
        $rows = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $start)
            ->select(
                DB::raw('YEARWEEK(completed_at, 3) as yw'),
                DB::raw('MIN(DATE(completed_at)) as week_start'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('yw')
            ->orderBy('yw')
            ->get();

        $byWeek = [];
        foreach ($rows as $r) {
            $byWeek[$r->yw] = [
                'label' => Carbon::parse($r->week_start)->format('d/m') . '-' . Carbon::parse($r->week_start)->addDays(6)->format('d/m'),
                'count' => (int) $r->count,
            ];
        }

        $labels = [];
        $data = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = Carbon::today()->subWeeks($i)->startOfWeek(Carbon::MONDAY);
            $yw = (int) $weekStart->isoWeekYear() * 100 + (int) $weekStart->isoWeek();
            $weekEnd = $weekStart->copy()->addDays(6);
            $labels[] = $byWeek[$yw]['label'] ?? ($weekStart->format('d/m') . '-' . $weekEnd->format('d/m'));
            $data[] = $byWeek[$yw]['count'] ?? 0;
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
