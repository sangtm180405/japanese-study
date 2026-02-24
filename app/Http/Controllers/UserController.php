<?php

namespace App\Http\Controllers;

use App\Models\MinnaLesson;
use App\Models\UserProgress;
use App\Models\Kanji;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalMinnaLessons = MinnaLesson::count();

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
            'currentStreak'
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
}

