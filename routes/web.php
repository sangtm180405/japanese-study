<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('home');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// User Dashboard
Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])
    ->middleware('auth')
    ->name('user.dashboard');

Route::get('/dashboard/progress', [App\Http\Controllers\UserController::class, 'progress'])
    ->middleware('auth')
    ->name('user.progress');

Route::get('/alphabet', [App\Http\Controllers\UserAlphabetController::class, 'index'])->name('alphabet.index');

Route::get('/flashcard', [App\Http\Controllers\FlashcardController::class, 'index'])->name('flashcard.index');
Route::get('/flashcard/bai-{number}', [App\Http\Controllers\FlashcardController::class, 'study'])->name('flashcard.study');
Route::get('/flashcard/study', [App\Http\Controllers\FlashcardController::class, 'study'])->name('flashcard.study.multi');

// Course Routes
Route::get('/courses', [App\Http\Controllers\CourseController::class, 'index'])->name('course.index');
Route::get('/course/{level}', [App\Http\Controllers\CourseController::class, 'show'])->name('course.show');
Route::get('/course/{level}/{sectionType}', [App\Http\Controllers\CourseController::class, 'showSection'])->name('course.section');
Route::get('/course/{level}/luyen-doc/{id}', [App\Http\Controllers\CourseController::class, 'showLuyenDocDetail'])->name('course.luyen-doc.detail');
Route::get('/course/{level}/marugoto-n5/{id}', [App\Http\Controllers\CourseController::class, 'showMarugotoDetail'])->name('course.marugoto.detail');
Route::get('/course/{level}/speed-master-n5/{bai}', [App\Http\Controllers\CourseController::class, 'showSpeedMasterDetail'])->name('course.speed-master.detail');

// Minna no Nihongo Routes
Route::prefix('minna')->name('minna.')->group(function () {
    Route::get('/', [App\Http\Controllers\MinnaController::class, 'index'])->name('index');
    Route::get('/bai-{number}', [App\Http\Controllers\MinnaController::class, 'show'])->name('show');
    Route::get('/bai-{number}/{sectionKey}', [App\Http\Controllers\MinnaController::class, 'showSection'])->name('section');

    Route::post('/bai-{number}/hoan-thanh', [App\Http\Controllers\MinnaController::class, 'complete'])
        ->middleware('auth')
        ->name('complete');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('alphabets', App\Http\Controllers\AlphabetController::class);
    Route::resource('kanjis', App\Http\Controllers\Admin\KanjiController::class);
    Route::resource('minna', App\Http\Controllers\Admin\MinnaController::class);
    Route::resource('course-data', App\Http\Controllers\Admin\CourseDataController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
});