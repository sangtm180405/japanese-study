<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcard - Ôn từ vựng Minna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
</head>
<body class="bg-gray-50">
    @include('layouts.header')

    <div class="container mx-auto px-4 max-w-5xl py-24">
        <a href="{{ route('minna.index') }}" class="text-red-600 hover:text-red-700 text-sm mb-6 inline-block">← Bài học Minna</a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Flashcard</h1>
        <p class="text-gray-600 mb-6">Chọn bài để ôn từ vựng. Có thể chọn nhiều bài để ôn cùng lúc. Nhấn thẻ để lật xem nghĩa.</p>

        {{-- Ôn nhiều bài --}}
        <form method="GET" action="{{ route('flashcard.study.multi') }}" id="form-multi" class="mb-8 p-4 bg-white rounded-xl border border-gray-200">
            <h2 class="font-semibold text-gray-900 mb-3">Ôn nhiều bài cùng lúc</h2>
            <div class="flex flex-wrap gap-3 mb-4">
                @foreach($lessonsWithCount as $item)
                    <label class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" name="bai[]" value="{{ $item['lesson']->number }}" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="font-medium">Bài {{ str_pad($item['lesson']->number, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-xs text-gray-500">({{ $item['count'] }} từ)</span>
                    </label>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2">
                <input type="hidden" name="shuffle" value="1">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                    Ôn các bài đã chọn
                </button>
                <button type="button" id="select-all" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    Chọn tất cả
                </button>
            </div>
        </form>

        {{-- Chọn 1 bài --}}
        <h2 class="font-semibold text-gray-900 mb-4">Hoặc chọn một bài</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($lessonsWithCount as $item)
                <a href="{{ route('flashcard.study', $item['lesson']->number) }}"
                   class="block bg-white rounded-xl border p-6 hover:shadow-lg hover:border-red-200 transition">
                    <span class="text-xl font-bold text-red-600">Bài {{ str_pad($item['lesson']->number, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="ml-2 text-sm bg-red-100 text-red-800 px-2 py-0.5 rounded">{{ $item['count'] }} từ</span>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900 jp" style="font-family: 'Hiragino Sans','Yu Gothic',sans-serif">{{ $item['lesson']->title }}</h3>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">Chưa có bài học. Chạy: php artisan db:seed --class=MinnaSeeder</div>
            @endforelse
        </div>
    </div>

        <script>
        document.getElementById('form-multi')?.addEventListener('submit', function(e) {
            const cbs = document.querySelectorAll('#form-multi input[name="bai[]"]:checked');
            if (cbs.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một bài học.');
            }
        });
        document.getElementById('select-all')?.addEventListener('click', function() {
            const cbs = document.querySelectorAll('#form-multi input[name="bai[]"]');
            const allChecked = Array.from(cbs).every(c => c.checked);
            cbs.forEach(c => c.checked = !allChecked);
            this.textContent = allChecked ? 'Chọn tất cả' : 'Bỏ chọn';
        });
    </script>
    @include('layouts.footer')
</body>
</html>
