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
     *
     * @return array{labels: string[], data: int[]}
     */
    public function getLessonsCompletedByDay(User $user, int $days = 7): array
    {
        $startOfRange = Carbon::today()->subDays($days - 1)->copy()->startOfDay();

        $dateExpr = $this->sqlDateOnly('completed_at');

        $rows = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $startOfRange)
            ->selectRaw("{$dateExpr} as d, COUNT(*) as aggregate")
            ->groupBy('d')
            ->pluck('aggregate', 'd')
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
     * Số bài Minna hoàn thành theo từng tuần (N tuần gần nhất), ISO tuần T2–CN — gom trong SQL.
     *
     * @return array{labels: string[], data: int[]}
     */
    public function getLessonsCompletedByWeek(User $user, int $weeks = 8): array
    {
        $start = Carbon::today()->subWeeks($weeks)->startOfWeek(Carbon::MONDAY);

        $weekKeyExpr = $this->sqlIsoWeekKey('completed_at');

        $rows = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $start)
            ->selectRaw("{$weekKeyExpr} as week_key, COUNT(*) as aggregate")
            ->groupBy('week_key')
            ->pluck('aggregate', 'week_key')
            ->mapWithKeys(fn ($count, $wk) => [(int) $wk => $count])
            ->all();

        $labels = [];
        $data = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = Carbon::today()->subWeeks($i)->startOfWeek(Carbon::MONDAY);
            $weekEnd = $weekStart->copy()->addDays(6);
            $key = $weekStart->isoWeekYear() * 100 + $weekStart->isoWeek();
            $labels[] = $weekStart->format('d/m').'-'.$weekEnd->format('d/m');
            $data[] = (int) ($rows[$key] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Tổng số bài đã hoàn thành và ước tính tổng từ vựng (từ các bài đã hoàn thành)
     */
    public function getSummary(User $user): array
    {
        $lessonIds = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->distinct()
            ->orderBy('lesson_id')
            ->pluck('lesson_id');

        $completedCount = $lessonIds->count();
        $totalVocab = $this->flashcardService->getTotalVocabCountByLessonIds($lessonIds->all());

        return [
            'completed_lessons' => $completedCount,
            'total_vocab_estimate' => $totalVocab,
        ];
    }

    private function sqlDateOnly(string $column): string
    {
        return match (DB::connection()->getDriverName()) {
            'mysql', 'mariadb' => "DATE({$column})",
            'sqlite' => "date({$column})",
            'pgsql' => "({$column})::date",
            default => "DATE({$column})",
        };
    }

    /**
     * Khóa số tuần ISO khớp Carbon: isoWeekYear * 100 + isoWeek.
     */
    private function sqlIsoWeekKey(string $column): string
    {
        return match (DB::connection()->getDriverName()) {
            'mysql', 'mariadb' => "YEARWEEK({$column}, 3)",
            'sqlite' => '(cast(strftime(\'%G\', '.$column.') as int) * 100 + cast(strftime(\'%V\', '.$column.') as int))',
            'pgsql' => "(to_char({$column}, 'IYYY')::int * 100 + to_char({$column}, 'IW')::int)",
            default => "YEARWEEK({$column}, 3)",
        };
    }
}
