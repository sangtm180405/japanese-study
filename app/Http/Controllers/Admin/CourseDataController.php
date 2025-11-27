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
            'content' => 'required|json',
            'order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['content'] = json_decode($request->content, true);

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
            'content' => 'required|json',
            'order' => 'required|integer',
        ]);

        $data = $request->all();
        $data['content'] = json_decode($request->content, true);

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
}
