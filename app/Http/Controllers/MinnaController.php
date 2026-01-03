<?php

namespace App\Http\Controllers;

use App\Services\MinnaService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class MinnaController extends Controller
{
    public function __construct(
        private MinnaService $minnaService
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

            return view('minna.show', compact('lesson', 'sectionsByKey', 'previousLessonNumber', 'nextLessonNumber'));
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

            return view('minna.section', compact('lesson', 'section'));
        } catch (InvalidArgumentException $e) {
            abort(404, $e->getMessage());
        }
    }
}

