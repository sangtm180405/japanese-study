<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use App\Services\StatisticsService;
use App\Services\UserDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private StatisticsService $statisticsService,
        private UserDashboardService $dashboardService,
    ) {}

    public function dashboard()
    {
        $user = Auth::user();
        $data = $this->dashboardService->getDashboardData($user);

        return view('user.dashboard', $data);
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

