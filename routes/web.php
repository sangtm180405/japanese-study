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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('alphabets', App\Http\Controllers\AlphabetController::class);
});