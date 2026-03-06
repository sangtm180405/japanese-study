<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('home');
})->middleware('throttle:study-get')->name('home');

// Auth Routes (throttle: 5 req/phút cho POST — tránh brute force)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post')
        ->middleware('throttle:login');

    Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post')
        ->middleware('throttle:register');
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])
        ->name('user.dashboard');

    Route::get('/dashboard/progress', [App\Http\Controllers\UserController::class, 'progress'])
        ->name('user.progress');

    Route::get('/dashboard/statistics', [App\Http\Controllers\UserController::class, 'statistics'])
        ->name('user.statistics');

    Route::get('/alphabet', [App\Http\Controllers\UserAlphabetController::class, 'index'])
        ->name('alphabet.index');

    // Ôn Kanji theo cấp (N5–N1)
    Route::prefix('kanji')->name('kanji.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserKanjiController::class, 'index'])
            ->middleware('throttle:study-get')
            ->name('index');

        Route::get('/{level}', [App\Http\Controllers\UserKanjiController::class, 'list'])
            ->middleware('throttle:study-get')
            ->name('list')
            ->where('level', 'N[1-5]');

        Route::get('/{level}/flashcard', [App\Http\Controllers\UserKanjiController::class, 'flashcard'])
            ->middleware('throttle:study-get')
            ->name('flashcard')
            ->where('level', 'N[1-5]');
    });

    Route::get('/tu-vung', [App\Http\Controllers\VocabularyController::class, 'index'])
        ->middleware('throttle:study-get')
        ->name('vocabulary.index');

    Route::get('/tu-vung/bai-{number}', [App\Http\Controllers\VocabularyController::class, 'show'])
        ->middleware('throttle:study-get')
        ->name('vocabulary.show')
        ->where('number', '[0-9]+');

    Route::get('/flashcard', [App\Http\Controllers\FlashcardController::class, 'index'])
        ->middleware('throttle:study-get')
        ->name('flashcard.index');

    Route::get('/flashcard/bai-{number}', [App\Http\Controllers\FlashcardController::class, 'study'])
        ->middleware('throttle:study-get')
        ->name('flashcard.study');

    Route::get('/flashcard/study', [App\Http\Controllers\FlashcardController::class, 'study'])
        ->middleware('throttle:study-get')
        ->name('flashcard.study.multi');

    // Course Routes
    Route::get('/courses', [App\Http\Controllers\CourseController::class, 'index'])
        ->middleware('throttle:study-get')
        ->name('course.index');

    Route::get('/course/{level}', [App\Http\Controllers\CourseController::class, 'show'])
        ->middleware('throttle:study-get')
        ->name('course.show');

    Route::get('/course/{level}/{sectionType}', [App\Http\Controllers\CourseController::class, 'showSection'])
        ->middleware('throttle:study-get')
        ->name('course.section');

    Route::get('/course/{level}/luyen-doc/{id}', [App\Http\Controllers\CourseController::class, 'showLuyenDocDetail'])
        ->middleware('throttle:study-get')
        ->name('course.luyen-doc.detail');

    Route::get('/course/{level}/marugoto-n5/{id}', [App\Http\Controllers\CourseController::class, 'showMarugotoDetail'])
        ->middleware('throttle:study-get')
        ->name('course.marugoto.detail');

    Route::get('/course/{level}/speed-master-n5/{bai}', [App\Http\Controllers\CourseController::class, 'showSpeedMasterDetail'])
        ->middleware('throttle:study-get')
        ->name('course.speed-master.detail');

    // Minna no Nihongo Routes
    Route::prefix('minna')->name('minna.')->group(function () {
        Route::get('/', [App\Http\Controllers\MinnaController::class, 'index'])
            ->middleware('throttle:study-get')
            ->name('index');

        Route::get('/bai-{number}', [App\Http\Controllers\MinnaController::class, 'show'])
            ->middleware('throttle:study-get')
            ->name('show');

        Route::get('/bai-{number}/{sectionKey}', [App\Http\Controllers\MinnaController::class, 'showSection'])
            ->middleware('throttle:study-get')
            ->name('section');

        Route::post('/bai-{number}/hoan-thanh', [App\Http\Controllers\MinnaController::class, 'complete'])
            ->middleware('throttle:study-post')
            ->name('complete');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'throttle:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('notifications/read-all', [App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::resource('alphabets', App\Http\Controllers\Admin\AlphabetController::class);
    Route::resource('kanjis', App\Http\Controllers\Admin\KanjiController::class);
    Route::resource('minna', App\Http\Controllers\Admin\MinnaController::class);
    Route::post('minna/{minna}/add-sections', [App\Http\Controllers\Admin\MinnaController::class, 'addSections'])->name('minna.add-sections');
    Route::get('minna-sections/{minnaSection}/edit', [App\Http\Controllers\Admin\MinnaSectionController::class, 'edit'])->name('minna-section.edit');
    Route::put('minna-sections/{minnaSection}', [App\Http\Controllers\Admin\MinnaSectionController::class, 'update'])->name('minna-section.update');
    Route::resource('course-data', App\Http\Controllers\Admin\CourseDataController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
});