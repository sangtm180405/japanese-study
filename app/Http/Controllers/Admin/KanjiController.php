<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kanji;
use App\Http\Requests\StoreKanjiRequest;
use App\Http\Requests\UpdateKanjiRequest;
use Illuminate\Http\Request;

class KanjiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kanji::query();

        // Filter by level
        if ($request->has('level') && $request->level) {
            $query->byLevel($request->level);
        }

        // Search by character, meaning, or reading
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('character', 'like', '%' . $request->search . '%')
                  ->orWhere('meaning', 'like', '%' . $request->search . '%')
                  ->orWhere('on_reading', 'like', '%' . $request->search . '%')
                  ->orWhere('kun_reading', 'like', '%' . $request->search . '%');
            });
        }

        $kanjis = $query->orderBy('level')->orderBy('character')->paginate(20);

        return view('admin.kanjis.index', compact('kanjis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kanjis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKanjiRequest $request)
    {
        Kanji::create($request->validated());

        return redirect()->route('admin.kanjis.index')
                        ->with('success', 'Kanji đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kanji $kanji)
    {
        return view('admin.kanjis.show', compact('kanji'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kanji $kanji)
    {
        return view('admin.kanjis.edit', compact('kanji'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKanjiRequest $request, Kanji $kanji)
    {
        $kanji->update($request->validated());

        return redirect()->route('admin.kanjis.index')
                        ->with('success', 'Kanji đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kanji $kanji)
    {
        $kanji->delete();

        return redirect()->route('admin.kanjis.index')
                        ->with('success', 'Kanji đã được xóa thành công!');
    }
}
