<!-- Header -->
<header class="fixed w-full top-0 bg-white/80 backdrop-blur-sm shadow-sm z-50">
    <nav class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition">
                    <span class="text-white text-2xl font-bold">日</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition">日本語</h1>
                    <p class="text-xs text-gray-500">Học tiếng Nhật hiệu quả</p>
                </div>
            </a>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Trang chủ
                </a>
                <a href="{{ route('minna.index') }}"
                   class="{{ request()->routeIs('minna.*') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Bài học Minna
                </a>
                <a href="{{ route('alphabet.index') }}"
                   class="{{ request()->routeIs('alphabet.index') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Bảng chữ cái
                </a>
            </div>
            
            <button class="bg-red-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-red-700 transition shadow-lg">
                Đăng nhập
            </button>
        </div>
    </nav>
</header>
