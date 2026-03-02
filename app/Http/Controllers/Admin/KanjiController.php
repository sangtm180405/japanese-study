<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kanji;
use App\Services\KanjiService;
use App\Http\Requests\StoreKanjiRequest;
use App\Http\Requests\UpdateKanjiRequest;
use App\Services\AlphabetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KanjiController extends Controller
{
    use PerPageTrait;

    public function __construct(
        private KanjiService $kanjiService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->kanjiService->getKanjisWithFilters(
            $request->get('level'),
            $request->get('search')
        );

        $kanjis = $query->paginate($this->adminPerPage($request))->withQueryString();

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
        Cache::forget('dashboard:total_kanjis');
        Cache::forget('admin:dashboard:stats');
        AlphabetService::clearAlphabetCache();

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
        Cache::forget('dashboard:total_kanjis');
        Cache::forget('admin:dashboard:stats');
        AlphabetService::clearAlphabetCache();

        return redirect()->route('admin.kanjis.index')
                        ->with('success', 'Kanji đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kanji $kanji)
    {
        $kanji->delete();
        Cache::forget('dashboard:total_kanjis');
        Cache::forget('admin:dashboard:stats');
        AlphabetService::clearAlphabetCache();

        return redirect()->route('admin.kanjis.index')
                        ->with('success', 'Kanji đã được xóa thành công!');
    }
}
