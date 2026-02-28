<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcard - Bài {{ $lesson->number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        .jp { font-family: 'Hiragino Sans','Yu Gothic','Meiryo',sans-serif; }
        .card-wrap { perspective: 1000px; }
        .card { transition: transform 0.5s; transform-style: preserve-3d; cursor: pointer; min-height: 200px; }
        .card.flip { transform: rotateY(180deg); }
        .face { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 2rem; border-radius: 1rem; }
        .front { background: linear-gradient(135deg,#fef2f2,#fee2e2); }
        .back { background: linear-gradient(135deg,#f0fdf4,#dcfce7); transform: rotateY(180deg); }
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.header')

    <div class="container mx-auto px-4 max-w-xl py-24">
        <a href="{{ route('flashcard.index') }}" class="text-red-600 hover:text-red-700 text-sm mb-6 inline-block">← Chọn bài khác</a>
        <h1 class="text-xl font-bold text-gray-900 mb-6 text-center">Bài {{ str_pad($lesson->number, 2, '0', STR_PAD_LEFT) }}</h1>
        <p class="text-center text-gray-500 text-sm mb-6">Thẻ <span id="idx">1</span>/{{ count($cards) }}</p>

        <div class="card-wrap mb-8">
            <div class="card relative" id="card">
                <div class="face front"><span class="jp text-3xl font-bold" id="f">{{ $cards[0]['front'] ?? '' }}</span><span class="text-xs text-gray-400 mt-2">Nhấn để lật</span></div>
                <div class="face back"><span class="jp text-2xl font-bold mb-2" id="bf">{{ $cards[0]['front'] ?? '' }}</span><span class="text-gray-700 text-center" id="bb">{{ $cards[0]['back'] ?? '' }}</span></div>
            </div>
        </div>

        <div class="flex gap-4">
            <button id="prev" class="flex-1 py-3 border rounded-lg disabled:opacity-50" {{ count($cards) <= 1 ? 'disabled' : '' }}>← Trước</button>
            <button id="next" class="flex-1 py-3 bg-red-600 text-white rounded-lg">Sau →</button>
        </div>
    </div>

    <script>
        const cards = @json($cards);
        const N = cards.length;
        let i = 0;
        const cardEl = document.getElementById('card');
        const f = document.getElementById('f'), bf = document.getElementById('bf'), bb = document.getElementById('bb');
        const prev = document.getElementById('prev'), next = document.getElementById('next');
        const idxEl = document.getElementById('idx');

        function go(n) {
            i = Math.max(0, Math.min(n, N - 1));
            const c = cards[i];
            f.textContent = bf.textContent = c.front;
            bb.textContent = c.back;
            cardEl.classList.remove('flip');
            idxEl.textContent = i + 1;
            prev.disabled = i === 0;
            next.textContent = i === N - 1 ? 'Xong' : 'Sau →';
        }
        cardEl.onclick = () => cardEl.classList.toggle('flip');
        prev.onclick = () => go(i - 1);
        next.onclick = () => i < N - 1 ? go(i + 1) : (location.href = '{{ route("flashcard.index") }}');
        document.onkeydown = e => { if (e.key === 'ArrowLeft') go(i - 1); else if (e.key === 'ArrowRight') i < N - 1 ? go(i + 1) : location.href = '{{ route("flashcard.index") }}'; };
    </script>
</body>
</html>
