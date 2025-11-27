<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MinnaLesson;
use App\Models\MinnaSection;
use Illuminate\Http\Request;

class MinnaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MinnaLesson::query();

        // Search by title or number
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('number', 'like', '%' . $request->search . '%');
            });
        }

        $lessons = $query->withCount('sections')->orderBy('number')->paginate(20);

        return view('admin.minna.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.minna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|unique:minna_lessons,number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MinnaLesson::create($request->only(['number', 'title', 'description']));

        return redirect()->route('admin.minna.index')
                        ->with('success', 'Bài học đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MinnaLesson $minna)
    {
        $minna->load('sections');
        return view('admin.minna.show', compact('minna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MinnaLesson $minna)
    {
        return view('admin.minna.edit', compact('minna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MinnaLesson $minna)
    {
        $request->validate([
            'number' => 'required|integer|unique:minna_lessons,number,' . $minna->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $minna->update($request->only(['number', 'title', 'description']));

        return redirect()->route('admin.minna.index')
                        ->with('success', 'Bài học đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MinnaLesson $minna)
    {
        $minna->delete();

        return redirect()->route('admin.minna.index')
                        ->with('success', 'Bài học đã được xóa thành công!');
    }
}
