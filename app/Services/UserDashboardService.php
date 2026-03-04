<?php

namespace App\Services;

use App\Models\Kanji;
use App\Models\MinnaLesson;
use App\Models\User;
use App\Models\UserProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UserDashboardService
{
    private const DASHBOARD_CACHE_TTL = 600; // 10 phút

    public function getDashboardData(User $user): array
    {
        $totalMinnaLessons = Cache::remember(
            'dashboard:total_minna_lessons',
            self::DASHBOARD_CACHE_TTL,
            fn () => MinnaLesson::count()
        );

        $totalKanjis = Cache::remember(
            'dashboard:total_kanjis',
            self::DASHBOARD_CACHE_TTL,
            fn () => Kanji::count()
        );

        $firstMinnaLesson = Cache::remember(
            'dashboard:first_minna_lesson',
            self::DASHBOARD_CACHE_TTL,
            fn () => MinnaLesson::query()->orderBy('number')->first()
        );

        $minnaProgresses = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->with('lesson:id,number,title')
            ->get();

        $completedMinnaLessons = $minnaProgresses
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->count();

        $inProgressMinnaLessons = $minnaProgresses
            ->where('status', UserProgress::STATUS_IN_PROGRESS)
            ->count();

        $latestMinnaProgress = $minnaProgresses
            ->whereNotNull('last_accessed_at')
            ->sortByDesc('last_accessed_at')
            ->first();

        $resumeMinnaLesson = $latestMinnaProgress?->lesson;
        $latestMinnaAccessAt = $latestMinnaProgress?->last_accessed_at;

        $dailyGoalTarget = 1;
        $today = Carbon::today()->toDateString();

        $todayCompletedMinnaLessons = $minnaProgresses
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->filter(
                fn ($p) => $p->completed_at && $p->completed_at->toDateString() === $today
            )
            ->count();

        $dailyGoalPercent = min(
            100,
            (int) round(($todayCompletedMinnaLessons / $dailyGoalTarget) * 100)
        );

        $isDailyGoalCompleted = $todayCompletedMinnaLessons >= $dailyGoalTarget;
        $remainingDailyLessons = max(0, $dailyGoalTarget - $todayCompletedMinnaLessons);

        $minnaProgressPercent = $totalMinnaLessons > 0
            ? round(($completedMinnaLessons / $totalMinnaLessons) * 100)
            : 0;

        $activityDates = UserProgress::query()
            ->where('user_id', $user->id)
            ->whereNotNull('last_accessed_at')
            ->pluck('last_accessed_at')
            ->map(
                fn ($v) => $v instanceof Carbon
                    ? $v->toDateString()
                    : Carbon::parse($v)->toDateString()
            )
            ->unique()
            ->values();

        $dateSet = $activityDates->flip();
        $currentStreak = 0;
        $cursor = Carbon::today();

        while ($dateSet->has($cursor->toDateString())) {
            $currentStreak++;
            $cursor->subDay();
        }

        return [
            'user' => $user,
            'completedMinnaLessons' => $completedMinnaLessons,
            'inProgressMinnaLessons' => $inProgressMinnaLessons,
            'minnaProgressPercent' => $minnaProgressPercent,
            'totalMinnaLessons' => $totalMinnaLessons,
            'totalKanjis' => $totalKanjis,
            'currentStreak' => $currentStreak,
            'firstMinnaLesson' => $firstMinnaLesson,
            'resumeMinnaLesson' => $resumeMinnaLesson,
            'latestMinnaAccessAt' => $latestMinnaAccessAt,
            'dailyGoalTarget' => $dailyGoalTarget,
            'todayCompletedMinnaLessons' => $todayCompletedMinnaLessons,
            'dailyGoalPercent' => $dailyGoalPercent,
            'isDailyGoalCompleted' => $isDailyGoalCompleted,
            'remainingDailyLessons' => $remainingDailyLessons,
        ];
    }
}

