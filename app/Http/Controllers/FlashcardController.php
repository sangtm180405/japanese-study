<?php

namespace App\Http\Controllers;

use App\Services\FlashcardService;

class FlashcardController extends Controller
{
    public function __construct(private FlashcardService $flashcardService) {}

    public function index()
    {
        $lessonsWithCount = $this->flashcardService->getLessonsWithVocabCount();
        return view('flashcard.index', compact('lessonsWithCount'));
    }

    public function study(int $number)
    {
        $result = $this->flashcardService->getFlashcardsByLesson($number);
        if (!$result['lesson'] || empty($result['cards'])) {
            abort(404, 'Không tìm thấy từ vựng cho bài này.');
        }
        return view('flashcard.study', $result);
    }
}
