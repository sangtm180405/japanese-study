<?php

namespace App\Http\Controllers;

use App\Models\Alphabet;
use App\Models\Kanji;
use Illuminate\Http\Request;

class UserAlphabetController extends Controller
{
    public function index()
    {
        // Gộp tất cả queries alphabet thành 1 query duy nhất
        $alphabets = Alphabet::whereIn('type', ['hiragana', 'katakana', 'romaji'])
            ->select('id', 'character', 'romaji', 'type', 'category')
            ->orderBy('type')
            ->orderBy('character')
            ->get();
        
        // Chia theo type
        $hiragana = $alphabets->where('type', 'hiragana')->values();
        $katakana = $alphabets->where('type', 'katakana')->values();
        $romaji = $alphabets->where('type', 'romaji')->values();
        
        // Gộp tất cả queries kanji thành 1 query duy nhất
        $kanjis = Kanji::whereIn('level', ['N5', 'N4', 'N3'])
            ->select('id', 'character', 'meaning', 'on_reading', 'kun_reading', 'level', 'stroke_count', 'radical', 'examples')
            ->orderBy('level')
            ->orderBy('character')
            ->get();
        
        // Chia theo level
        $kanjiN5 = $kanjis->where('level', 'N5')->values();
        $kanjiN4 = $kanjis->where('level', 'N4')->values();
        $kanjiN3 = $kanjis->where('level', 'N3')->values();
        
        return view('user.alphabet.alphabet', compact('hiragana', 'katakana', 'romaji', 'kanjiN5', 'kanjiN4', 'kanjiN3'));
    }
}