<?php

namespace App\Http\Controllers;

use App\Models\MinnaLesson;
use App\Services\MinnaService;
use App\Services\UserProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class MinnaController extends Controller
{
    public function __construct(
        private MinnaService $minnaService,
        private UserProgressService $userProgressService
    ) {}

    /**
     * Hiển thị danh sách tất cả các bài học
     */
    public function index()
    {
        $lessons = $this->minnaService->getAllLessons();
        
        return view('minna.index', compact('lessons'));
    }

    /**
     * Hiển thị chi tiết một bài học
     */
    public function show($number)
    {
        try {
            $lesson = $this->minnaService->getLessonByNumber($number);
            $sectionsByKey = $this->minnaService->groupSectionsByKey($lesson->sections);
            $previousLessonNumber = $this->minnaService->getPreviousLessonNumber($lesson->number);
            $nextLessonNumber = $this->minnaService->getNextLessonNumber($lesson->number);

            $progress = null;
            if (Auth::check()) {
                $progress = $this->userProgressService->touchMinnaLesson(Auth::user(), $lesson);
            }

            return view('minna.show', compact(
                'lesson',
                'sectionsByKey',
                'previousLessonNumber',
                'nextLessonNumber',
                'progress'
            ));
        } catch (InvalidArgumentException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Hiển thị một section cụ thể của bài học
     */
    public function showSection($number, $sectionKey)
    {
        try {
            $section = $this->minnaService->getSectionByLessonAndKey($number, $sectionKey);
            $lesson = $section->lesson;

            if (Auth::check()) {
                $this->userProgressService->touchMinnaLesson(Auth::user(), $lesson);
            }

            return view('minna.section', compact('lesson', 'section'));
        } catch (InvalidArgumentException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Đánh dấu bài học là đã hoàn thành.
     */
    public function complete($number)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            /** @var MinnaLesson $lesson */
            $lesson = $this->minnaService->getLessonByNumber((int) $number);

            $this->userProgressService->markMinnaLessonCompleted(Auth::user(), $lesson);

            return redirect()
                ->route('minna.show', ['number' => $lesson->number])
                ->with('status', 'Đã đánh dấu bài học là hoàn thành.');
        } catch (InvalidArgumentException $e) {
            abort(404, $e->getMessage());
        }
    }
}
