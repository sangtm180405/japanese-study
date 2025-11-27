<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alphabet;
use App\Models\Kanji;
use App\Models\MinnaLesson;
use App\Models\N5CourseData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'total_alphabets' => Alphabet::count(),
            'total_kanjis' => Kanji::count(),
            'total_minna_lessons' => MinnaLesson::count(),
            'total_n5_course_data' => N5CourseData::count(),
            'kanjis_by_level' => Kanji::selectRaw('level, count(*) as count')
                ->groupBy('level')
                ->pluck('count', 'level')
                ->toArray(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
