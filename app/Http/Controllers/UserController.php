<?php

namespace App\Http\Controllers;

use App\Models\MinnaLesson;
use App\Models\UserProgress;
use App\Models\Kanji;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    private const DASHBOARD_CACHE_TTL = 600; // 10 phút

    public function __construct(
        private StatisticsService $statisticsService
    ) {}

    public function dashboard()
    {
        $user = Auth::user();

        // Cache tổng số bài Minna, tổng Kanji, bài đầu (ít thay đổi, admin mới sửa)
        $totalMinnaLessons = Cache::remember('dashboard:total_minna_lessons', self::DASHBOARD_CACHE_TTL, fn () => MinnaLesson::count());
        $totalKanjis = Cache::remember('dashboard:total_kanjis', self::DASHBOARD_CACHE_TTL, fn () => Kanji::count());
        $firstMinnaLesson = Cache::remember('dashboard:first_minna_lesson', self::DASHBOARD_CACHE_TTL, fn () => MinnaLesson::query()->orderBy('number')->first());

        // Một query lấy toàn bộ tiến độ Minna của user (kèm lesson) → tính toán trong PHP
        $minnaProgresses = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->with('lesson:id,number,title')
            ->get();

        $completedMinnaLessons = $minnaProgresses->where('status', UserProgress::STATUS_COMPLETED)->count();
        $inProgressMinnaLessons = $minnaProgresses->where('status', UserProgress::STATUS_IN_PROGRESS)->count();

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
            ->filter(fn ($p) => $p->completed_at && $p->completed_at->toDateString() === $today)
            ->count();
        $dailyGoalPercent = min(100, (int) round(($todayCompletedMinnaLessons / $dailyGoalTarget) * 100));
        $isDailyGoalCompleted = $todayCompletedMinnaLessons >= $dailyGoalTarget;
        $remainingDailyLessons = max(0, $dailyGoalTarget - $todayCompletedMinnaLessons);

        $minnaProgressPercent = $totalMinnaLessons > 0
            ? round(($completedMinnaLessons / $totalMinnaLessons) * 100)
            : 0;

        // Streak: mọi hoạt động (mọi lesson_type), query nhẹ chỉ pluck ngày
        $activityDates = UserProgress::query()
            ->where('user_id', $user->id)
            ->whereNotNull('last_accessed_at')
            ->pluck('last_accessed_at')
            ->map(fn ($v) => $v instanceof Carbon ? $v->toDateString() : Carbon::parse($v)->toDateString())
            ->unique()
            ->values();
        $dateSet = $activityDates->flip();
        $currentStreak = 0;
        $cursor = Carbon::today();
        while ($dateSet->has($cursor->toDateString())) {
            $currentStreak++;
            $cursor->subDay();
        }

        return view('user.dashboard', compact(
            'user',
            'completedMinnaLessons',
            'inProgressMinnaLessons',
            'minnaProgressPercent',
            'totalMinnaLessons',
            'totalKanjis',
            'currentStreak',
            'firstMinnaLesson',
            'resumeMinnaLesson',
            'latestMinnaAccessAt',
            'dailyGoalTarget',
            'todayCompletedMinnaLessons',
            'dailyGoalPercent',
            'isDailyGoalCompleted',
            'remainingDailyLessons'
        ));
    }

    public function progress()
    {
        $user = Auth::user();

        $minnaProgresses = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->with('lesson:id,number,title,description')
            ->orderByDesc('last_accessed_at')
            ->get();

        return view('user.progress', [
            'user' => $user,
            'minnaProgresses' => $minnaProgresses,
        ]);
    }

    /**
     * Thống kê chi tiết: biểu đồ theo ngày, tuần, tổng bài/từ
     */
    public function statistics()
    {
        $user = Auth::user();

        $byDay = $this->statisticsService->getLessonsCompletedByDay($user, 7);
        $byWeek = $this->statisticsService->getLessonsCompletedByWeek($user, 8);
        $summary = $this->statisticsService->getSummary($user);

        return view('user.statistics', [
            'user' => $user,
            'byDay' => $byDay,
            'byWeek' => $byWeek,
            'summary' => $summary,
        ]);
    }
}

