<?php

namespace App\Http\Controllers;

use App\Services\FlashcardService;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function __construct(private FlashcardService $flashcardService) {}

    public function index()
    {
        $lessonsWithCount = $this->flashcardService->getLessonsWithVocabCount();
        return view('flashcard.index', compact('lessonsWithCount'));
    }

    public function study(Request $request, ?int $number = null)
    {
        $numbers = $request->input('bai');
        if (is_string($numbers)) {
            $numbers = array_filter(array_map('intval', explode(',', $numbers)));
        }
        if (empty($numbers) || !is_array($numbers)) {
            $numbers = $number ? [$number] : [1];
        }

        $result = $this->flashcardService->getFlashcardsByLessons(
            $numbers,
            (bool) $request->query('shuffle', false)
        );

        if (empty($result['cards'])) {
            abort(404, 'Không tìm thấy từ vựng cho bài đã chọn.');
        }

        return view('flashcard.study', [
            'lesson' => $result['lessons'][0] ?? null,
            'lessons' => $result['lessons'],
            'cards' => $result['cards'],
            'reverse' => (bool) $request->query('reverse', false),
        ]);
    }
}
