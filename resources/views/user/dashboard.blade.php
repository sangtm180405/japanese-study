<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
</head>
<body class="bg-gray-50">
    @include('layouts.header')

    <div class="pt-24 pb-12 min-h-screen">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Xin chào, {{ $user->name }}!</h1>
                <p class="text-gray-600">Chào mừng bạn đến với dashboard của bạn</p>
            </div>

            @if(!$isDailyGoalCompleted)
                <div class="mb-8 rounded-lg border border-amber-300 bg-amber-50 p-4 md:p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-amber-800">Nhắc học hôm nay</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Bạn còn {{ $remainingDailyLessons }} bài Minna để hoàn thành mục tiêu hôm nay.
                            </p>
                        </div>
                        @if($resumeMinnaLesson)
                            <a href="{{ route('minna.show', ['number' => $resumeMinnaLesson->number]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-md transition">
                                Học ngay bài {{ $resumeMinnaLesson->number }}
                            </a>
                        @elseif($firstMinnaLesson)
                            <a href="{{ route('minna.show', ['number' => $firstMinnaLesson->number]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-md transition">
                                Bắt đầu học ngay
                            </a>
                        @else
                            <a href="{{ route('minna.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-md transition">
                                Vào khu vực Minna
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="mb-8 rounded-lg border border-green-300 bg-green-50 p-4 md:p-5">
                    <p class="text-sm font-semibold text-green-700">
                        Hoàn thành mục tiêu hôm nay. Tiếp tục giữ streak nhé!
                    </p>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bài học đã học</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $completedMinnaLessons ?? 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tổng số Kanji</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $totalKanjis ?? 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Ngày học liên tiếp</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $currentStreak ?? 0 }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Hành động nhanh</h2>
                    <div class="space-y-3">
                        <a href="{{ route('alphabet.index') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2.5 rounded transition">
                            Học bảng chữ cái
                        </a>
                        <a href="{{ route('flashcard.index') }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-center py-2.5 rounded transition">
                            Ôn Flashcard
                        </a>
                        @if($resumeMinnaLesson)
                            <a href="{{ route('minna.show', ['number' => $resumeMinnaLesson->number]) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2.5 rounded transition">
                                Học tiếp Minna - Bài {{ $resumeMinnaLesson->number }}
                            </a>
                        @elseif($firstMinnaLesson)
                            <a href="{{ route('minna.show', ['number' => $firstMinnaLesson->number]) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2.5 rounded transition">
                                Bắt đầu Minna - Bài {{ $firstMinnaLesson->number }}
                            </a>
                        @else
                            <a href="{{ route('minna.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2.5 rounded transition">
                                Bài học Minna no Nihongo
                            </a>
                        @endif

                        @if($latestMinnaAccessAt)
                            <p class="text-xs text-gray-500">
                                Lần học Minna gần nhất: {{ $latestMinnaAccessAt->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tiến độ học tập</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Minna no Nihongo</span>
                                <span class="text-gray-900 font-medium">
                                    {{ $minnaProgressPercent ?? 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ $minnaProgressPercent ?? 0 }}%"
                                ></div>
                            </div>
                        </div>
                        <div class="pt-1">
                            <a
                                href="{{ route('user.progress') }}"
                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium"
                            >
                                Xem chi tiết tiến độ
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">Mục tiêu hôm nay</span>
                                <span class="font-medium {{ $isDailyGoalCompleted ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ min($todayCompletedMinnaLessons, $dailyGoalTarget) }}/{{ $dailyGoalTarget }} bài
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="{{ $isDailyGoalCompleted ? 'bg-green-600' : 'bg-amber-500' }} h-2 rounded-full"
                                    style="width: {{ $dailyGoalPercent }}%"
                                ></div>
                            </div>
                            <p class="text-xs mt-2 {{ $isDailyGoalCompleted ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $isDailyGoalCompleted ? 'Bạn đã hoàn thành mục tiêu hôm nay.' : 'Hoàn thành ít nhất 1 bài Minna trong ngày để giữ phong độ học.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">Thông tin tài khoản</h2>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>
</html>

