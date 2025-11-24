<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/admin', function () {
    return view('admin.admin');
})->name('admin.dashboard');

Route::get('/alphabet', [App\Http\Controllers\UserAlphabetController::class, 'index'])->name('alphabet.index');

// Minna no Nihongo Routes
Route::prefix('minna')->name('minna.')->group(function () {
    Route::get('/', [App\Http\Controllers\MinnaController::class, 'index'])->name('index');
    Route::get('/bai-{number}', [App\Http\Controllers\MinnaController::class, 'show'])->name('show');
    Route::get('/bai-{number}/{sectionKey}', [App\Http\Controllers\MinnaController::class, 'showSection'])->name('section');
});

// Admin Alphabet CRUD Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('alphabets', App\Http\Controllers\AlphabetController::class);
});