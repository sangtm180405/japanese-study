<?php

namespace App\Http\Controllers;

use App\Models\MinnaLesson;
use App\Models\UserProgress;
use App\Models\Kanji;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private StatisticsService $statisticsService
    ) {}
    public function dashboard()
    {
        $user = Auth::user();

        $totalMinnaLessons = MinnaLesson::count();
        $firstMinnaLesson = MinnaLesson::query()
            ->orderBy('number')
            ->first();

        $completedMinnaLessons = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->count();

        $inProgressMinnaLessons = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_IN_PROGRESS)
            ->count();

        $latestMinnaProgress = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->orderByDesc('last_accessed_at')
            ->orderByDesc('updated_at')
            ->first();

        $resumeMinnaLesson = null;
        $latestMinnaAccessAt = null;
        if ($latestMinnaProgress) {
            $resumeMinnaLesson = MinnaLesson::query()
                ->where('id', $latestMinnaProgress->lesson_id)
                ->first();
            $latestMinnaAccessAt = $latestMinnaProgress->last_accessed_at;
        }

        $dailyGoalTarget = 1;
        $todayCompletedMinnaLessons = UserProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_type', UserProgress::TYPE_MINNA)
            ->where('status', UserProgress::STATUS_COMPLETED)
            ->whereDate('completed_at', Carbon::today())
            ->count();
        $dailyGoalPercent = min(100, (int) round(($todayCompletedMinnaLessons / $dailyGoalTarget) * 100));
        $isDailyGoalCompleted = $todayCompletedMinnaLessons >= $dailyGoalTarget;
        $remainingDailyLessons = max(0, $dailyGoalTarget - $todayCompletedMinnaLessons);

        $minnaProgressPercent = $totalMinnaLessons > 0
            ? round(($completedMinnaLessons / $totalMinnaLessons) * 100)
            : 0;

        // Thống kê Kanji (tổng số kanji trong hệ thống)
        $totalKanjis = Kanji::count();

        // Tính ngày học liên tiếp (streak) dựa trên ngày có hoạt động học
        $activityDates = UserProgress::query()
            ->where('user_id', $user->id)
            ->whereNotNull('last_accessed_at')
            ->pluck('last_accessed_at')
            ->map(static function ($value) {
                return $value instanceof \Illuminate\Support\Carbon
                    ? $value->toDateString()
                    : Carbon::parse($value)->toDateString();
            })
            ->unique()
            ->values();

        $dateSet = $activityDates->flip(); // dateString => index
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
            ->orderByDesc('last_accessed_at')
            ->with('user') // phòng trường hợp cần trong view
            ->get();

        $lessons = MinnaLesson::whereIn('id', $minnaProgresses->pluck('lesson_id')->all())
            ->get()
            ->keyBy('id');

        return view('user.progress', [
            'user' => $user,
            'minnaProgresses' => $minnaProgresses,
            'lessons' => $lessons,
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

