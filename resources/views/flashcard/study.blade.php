<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcard - {{ ($lesson->title ?? 'Ôn từ vựng') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        .jp { font-family: 'Hiragino Sans','Yu Gothic','Meiryo',sans-serif; }
        .card-wrap { perspective: 1000px; }
        .card { transition: transform 0.5s; transform-style: preserve-3d; cursor: pointer; min-height: 220px; }
        .card.flip { transform: rotateY(180deg); }
        .face { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 2rem; border-radius: 1rem; left: 0; top: 0; }
        .front { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 2px solid #fecaca; }
        .back { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 2px solid #bbf7d0; transform: rotateY(180deg); }
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.header')

    <div class="container mx-auto px-4 max-w-2xl py-24">
        <a href="{{ route('flashcard.index') }}" class="text-red-600 hover:text-red-700 text-sm mb-6 inline-block">← Chọn bài khác</a>

        {{-- Tiêu đề: 1 bài hoặc nhiều bài --}}
        <h1 class="text-xl font-bold text-gray-900 mb-2 text-center">
            @if(isset($lessons) && count($lessons) > 1)
                Ôn từ bài {{ min(array_map(fn($l) => $l->number, $lessons)) }} đến {{ max(array_map(fn($l) => $l->number, $lessons)) }}
            @elseif($lesson)
                Bài {{ str_pad($lesson->number, 2, '0', STR_PAD_LEFT) }} - {{ $lesson->title }}
            @else
                Ôn từ vựng
            @endif
        </h1>

        {{-- Thanh công cụ --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['shuffle' => request()->query('shuffle') ? '0' : '1'])) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ request()->query('shuffle') ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    🔀 Xáo trộn
                </a>
                <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['reverse' => request()->query('reverse') ? '0' : '1'])) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ request()->query('reverse') ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    ↩️ Đảo thẻ
                </a>
            </div>
            <button type="button" id="btn-speak" class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" title="Phát âm">
                🔊 Phát âm
            </button>
        </div>

        <p class="text-center text-gray-500 text-sm mb-4">
            Thẻ <span id="idx">1</span>/{{ count($cards) }}
            <span class="text-gray-400 text-xs block mt-1">Space: lật • ← →: chuyển thẻ</span>
        </p>

        {{-- Thanh tiến độ --}}
        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
            <div id="progress-bar" class="bg-red-600 h-2 rounded-full transition-all duration-300" style="width: {{ count($cards) ? (100 / count($cards)) : 0 }}%"></div>
        </div>

        <div class="card-wrap mb-8">
            <div class="card relative" id="card">
                @php
                    $reverse = request()->query('reverse', false);
                    $frontText = $reverse ? ($cards[0]['back'] ?? '') : ($cards[0]['front'] ?? '');
                    $backText = $reverse ? ($cards[0]['front'] ?? '') : ($cards[0]['back'] ?? '');
                @endphp
                <div class="face front">
                    <span class="jp text-3xl font-bold text-center" id="f">{{ $frontText }}</span>
                    @if(isset($cards[0]['lesson_number']))
                        <span class="text-xs text-gray-500 mt-2">Bài {{ $cards[0]['lesson_number'] }}</span>
                    @endif
                    <span class="text-xs text-gray-400 mt-2">Nhấn để lật</span>
                </div>
                <div class="face back">
                    <span class="jp text-2xl font-bold mb-2 text-center" id="bf">{{ $reverse ? ($cards[0]['back'] ?? '') : ($cards[0]['front'] ?? '') }}</span>
                    @if(isset($cards[0]['lesson_number']))
                        <span class="text-xs text-gray-500 mb-1">Bài {{ $cards[0]['lesson_number'] }}</span>
                    @endif
                    <span class="text-gray-700 text-center text-lg" id="bb">{{ $reverse ? ($cards[0]['front'] ?? '') : ($cards[0]['back'] ?? '') }}</span>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button id="prev" class="flex-1 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed font-medium" {{ count($cards) <= 1 ? 'disabled' : '' }}>← Trước</button>
            <button id="next" class="flex-1 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Sau →</button>
        </div>
    </div>

    <script>
        (function() {
            const reverse = {{ request()->query('reverse') ? 'true' : 'false' }};
            let cards = @json($cards);
            const N = cards.length;
            let i = 0;

            const cardEl = document.getElementById('card');
            const f = document.getElementById('f'), bf = document.getElementById('bf'), bb = document.getElementById('bb');
            const prev = document.getElementById('prev'), next = document.getElementById('next');
            const idxEl = document.getElementById('idx');
            const progressBar = document.getElementById('progress-bar');
            const btnSpeak = document.getElementById('btn-speak');

            function getFront(c) { return reverse ? c.back : c.front; }
            function getBack(c) { return reverse ? c.front : c.back; }

            function go(n) {
                i = Math.max(0, Math.min(n, N - 1));
                const c = cards[i];
                f.textContent = getFront(c);
                bf.textContent = getFront(c);
                bb.textContent = getBack(c);
                cardEl.classList.remove('flip');
                idxEl.textContent = i + 1;
                progressBar.style.width = ((i + 1) / N * 100) + '%';
                prev.disabled = i === 0;
                next.textContent = i === N - 1 ? 'Xong' : 'Sau →';
            }

            cardEl.onclick = () => cardEl.classList.toggle('flip');

            prev.onclick = () => go(i - 1);
            next.onclick = () => i < N - 1 ? go(i + 1) : (location.href = '{{ route("flashcard.index") }}');

            document.onkeydown = function(e) {
                if (e.key === ' ') { e.preventDefault(); cardEl.classList.toggle('flip'); return; }
                if (e.key === 'ArrowLeft') { go(i - 1); return; }
                if (e.key === 'ArrowRight') { i < N - 1 ? go(i + 1) : (location.href = '{{ route("flashcard.index") }}'); }
            };

            // Phát âm (Web Speech API - tiếng Nhật)
            if (btnSpeak && ('speechSynthesis' in window)) {
                btnSpeak.onclick = function() {
                    const text = cardEl.classList.contains('flip') ? getBack(cards[i]) : getFront(cards[i]);
                    if (!text) return;
                    const u = new SpeechSynthesisUtterance(text);
                    u.lang = 'ja-JP';
                    u.rate = 0.9;
                    speechSynthesis.cancel();
                    speechSynthesis.speak(u);
                };
            } else if (btnSpeak) {
                btnSpeak.style.display = 'none';
            }
        })();
    </script>
</body>
</html>
