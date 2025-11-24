<?php

namespace App\Http\Controllers;

use App\Models\MinnaLesson;
use App\Models\MinnaSection;
use Illuminate\Http\Request;

class MinnaController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các bài học
     */
    public function index()
    {
        // Chỉ lấy các cột cần dùng trong view để giảm tải
        $lessons = MinnaLesson::select('id', 'number', 'title', 'description')
            ->orderBy('number')
            ->get();
        
        return view('minna.index', compact('lessons'));
    }

    /**
     * Hiển thị chi tiết một bài học
     */
    public function show($number)
    {
        $lesson = MinnaLesson::select('id', 'number', 'title', 'description')
            ->where('number', $number)
            ->with(['sections' => function($query) {
                $query->select('id', 'lesson_id', 'order_index', 'key', 'title', 'content', 'media_url')
                      ->orderBy('order_index');
            }])
            ->firstOrFail();

        // Nhóm sections theo key để dễ hiển thị và giữ nhiều block cùng loại
        $sectionsByKey = $lesson->sections
            ->groupBy('key');

        $previousLessonNumber = MinnaLesson::where('number', '<', $lesson->number)
            ->orderByDesc('number')
            ->value('number');

        $nextLessonNumber = MinnaLesson::where('number', '>', $lesson->number)
            ->orderBy('number')
            ->value('number');

        return view('minna.show', compact('lesson', 'sectionsByKey', 'previousLessonNumber', 'nextLessonNumber'));
    }

    /**
     * Hiển thị một section cụ thể của bài học
     * Tối ưu: Gộp 2 queries thành 1 bằng join
     */
    public function showSection($number, $sectionKey)
    {
        // Gộp 2 queries thành 1 bằng join để tránh N+1
        $section = MinnaSection::select('id', 'lesson_id', 'order_index', 'key', 'title', 'content', 'media_url')
            ->whereHas('lesson', function($query) use ($number) {
                $query->where('number', $number);
            })
            ->where('key', $sectionKey)
            ->with('lesson:id,number,title,description')
            ->firstOrFail();
        
        $lesson = $section->lesson;

        return view('minna.section', compact('lesson', 'section'));
    }
}

