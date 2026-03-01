<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\N5CourseData;
use Illuminate\Http\Request;

class CourseDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = N5CourseData::query();

        // Filter by section_type
        if ($request->has('section_type') && $request->section_type) {
            $query->where('section_type', $request->section_type);
        }

        // Filter by section_key
        if ($request->has('section_key') && $request->section_key) {
            $query->where('section_key', $request->section_key);
        }

        // Search by title or bai
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('bai', 'like', '%' . $request->search . '%');
            });
        }

        $courseData = $query->orderBy('section_type')->orderBy('order')->paginate(20);

        return view('admin.course-data.index', compact('courseData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.course-data.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_type' => 'required|string|max:255',
            'section_key' => 'nullable|string|max:255',
            'bai' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable',
            'order' => 'required|integer',
        ]);

        $data = $request->only(['section_type', 'section_key', 'bai', 'title', 'order']);
        try {
            $data['content'] = $this->normalizeContent($request);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['content' => $e->getMessage()])->withInput();
        }

        N5CourseData::create($data);

        return redirect()->route('admin.course-data.index')
                        ->with('success', 'Course data đã được thêm thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(N5CourseData $courseData)
    {
        return view('admin.course-data.edit', compact('courseData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, N5CourseData $courseData)
    {
        $request->validate([
            'section_type' => 'required|string|max:255',
            'section_key' => 'nullable|string|max:255',
            'bai' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable',
            'order' => 'required|integer',
        ]);

        $data = $request->only(['section_type', 'section_key', 'bai', 'title', 'order']);
        try {
            $data['content'] = $this->normalizeContent($request, $courseData);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['content' => $e->getMessage()])->withInput();
        }

        $courseData->update($data);

        return redirect()->route('admin.course-data.index')
                        ->with('success', 'Course data đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(N5CourseData $courseData)
    {
        $courseData->delete();

        return redirect()->route('admin.course-data.index')
                        ->with('success', 'Course data đã được xóa thành công!');
    }

    /**
     * Chuẩn hóa content từ form hoặc JSON
     */
    private function normalizeContent(Request $request, ?N5CourseData $courseData = null): array
    {
        $content = $request->content;
        $sectionType = $request->section_type ?? $courseData?->section_type ?? '';
        $sectionKey = $request->section_key ?? $courseData?->section_key ?? '';

        // Khi không có form content (no-editor), giữ nguyên content hiện tại khi update
        if ($content === null && $courseData) {
            return $courseData->content ?? [];
        }

        if (is_array($content)) {
            if ($sectionKey === 'tuVung' && $sectionType !== 'marugoto_n5') {
                return $this->normalizeWords($content);
            }
            if ($sectionType === 'luyen_doc') {
                return $this->normalizeLuyenDoc($content);
            }
            if ($sectionKey === 'nguPhap') {
                return $this->normalizeNguPhap($content);
            }
            if ($sectionKey === 'docHieu' || $sectionKey === 'ngheHieu') {
                return $this->normalizeDocHieu($content);
            }
            if ($sectionType === 'marugoto_n5') {
                return $this->normalizeMarugoto($content);
            }
        }

        return [];
    }

    private function normalizeWords(array $content): array
    {
        $words = [];
        foreach ($content as $row) {
            if (!is_array($row)) continue;
            $tu = trim($row['tu'] ?? '');
            $nghia = trim($row['nghia'] ?? '');
            if ($tu !== '' || $nghia !== '') {
                $words[] = ['tu' => $tu, 'nghia' => $nghia];
            }
        }
        return $words;
    }

    private function normalizeLuyenDoc(array $content): array
    {
        $passage = trim($content['passage'] ?? '');
        $questions = $content['questions'] ?? [];
        if (!is_array($questions)) {
            $questions = [];
        }
        $result = ['passage' => $passage, 'questions' => []];
        foreach ($questions as $q) {
            if (!is_array($q)) continue;
            $optText = trim($q['options_text'] ?? '');
            $options = [];
            foreach (preg_split('/\r\n|\r|\n/', $optText) as $line) {
                $line = trim($line);
                if ($line === '') continue;
                $parts = array_map('trim', explode('|', $line, 3));
                $opt = ['text' => $parts[0] ?? ''];
                if (isset($parts[1]) && $parts[1] !== '') $opt['romaji'] = $parts[1];
                if (isset($parts[2]) && $parts[2] !== '') $opt['meaning'] = $parts[2];
                $options[] = $opt;
            }
            $result['questions'][] = [
                'question_number' => trim($q['question_number'] ?? ''),
                'question' => trim($q['question'] ?? ''),
                'options' => $options,
                'correct_answer' => (int) ($q['correct_answer'] ?? 0),
                'explanation' => trim($q['explanation'] ?? '') ?: null,
            ];
        }
        return $result;
    }

    private function normalizeNguPhap(array $content): array
    {
        $points = [];
        foreach ($content as $row) {
            if (!is_array($row)) continue;
            $particle = trim($row['particle'] ?? '');
            $explanation = trim($row['explanation'] ?? '');
            if ($particle === '' && $explanation === '') continue;
            $examples = [];
            $optText = trim($row['examples_text'] ?? '');
            foreach (preg_split('/\r\n|\r|\n/', $optText) as $line) {
                $line = trim($line);
                if ($line === '') continue;
                $parts = array_map('trim', explode('|', $line, 2));
                $examples[] = ['japanese' => $parts[0] ?? '', 'vietnamese' => $parts[1] ?? ''];
            }
            $points[] = array_filter([
                'particle' => $particle,
                'explanation' => $explanation,
                'examples' => $examples,
            ], fn($v) => $v !== '' && $v !== []);
        }
        return $points;
    }

    private function normalizeDocHieu(array $content): array
    {
        $options = [];
        foreach ($content['options'] ?? [] as $opt) {
            if (!is_string($opt)) continue;
            $parts = array_map('trim', explode('|', $opt, 3));
            $o = ['text' => $parts[0] ?? ''];
            if (isset($parts[1]) && $parts[1] !== '') $o['romaji'] = $parts[1];
            if (isset($parts[2]) && $parts[2] !== '') $o['meaning'] = $parts[2];
            if ($o['text'] !== '') $options[] = $o;
        }
        return array_filter([
            'passage' => trim($content['passage'] ?? ''),
            'question' => trim($content['question'] ?? ''),
            'options' => $options,
            'correct_answer' => (int) ($content['correct_answer'] ?? 0),
            'explanation' => trim($content['explanation'] ?? '') ?: null,
        ], fn($v) => $v !== '' && $v !== null);
    }

    private function normalizeMarugoto(array $content): array
    {
        $tuVung = $this->normalizeWords($content['tuVung'] ?? []);
        $nguPhap = $this->normalizeNguPhap($content['nguPhap'] ?? []);
        $result = [];
        if (!empty($tuVung)) $result['tuVung'] = $tuVung;
        if (!empty($nguPhap)) $result['nguPhap'] = $nguPhap;
        return $result;
    }
}
