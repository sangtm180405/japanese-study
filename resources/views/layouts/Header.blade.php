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
                <a href="{{ route('course.index') }}"
                   class="{{ request()->routeIs('course.index') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Tổng hợp N
                </a>
                @auth
                <a href="{{ route('user.dashboard') }}"
                   class="{{ request()->routeIs('user.dashboard') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Dashboard
                </a>
                @endauth
                <a href="{{ route('minna.index') }}"
                   class="{{ request()->routeIs('minna.*') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Bài học Minna
                </a>
                <a href="{{ route('alphabet.index') }}"
                   class="{{ request()->routeIs('alphabet.index') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600 font-medium' }} transition">
                    Bảng chữ cái
                </a>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Mobile menu button -->
                <button type="button"
                        id="mobile-menu-toggle"
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 text-gray-700 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <span class="sr-only">Mở menu</span>
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="7" x2="20" y2="7" stroke-linecap="round"></line>
                        <line x1="4" y1="12" x2="20" y2="12" stroke-linecap="round"></line>
                        <line x1="4" y1="17" x2="20" y2="17" stroke-linecap="round"></line>
                    </svg>
                </button>

                @auth
                <div class="hidden md:flex items-center space-x-3">
                    <span class="hidden md:inline text-sm text-gray-700">
                        Xin chào, <span class="font-semibold">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->role === 'admin')
                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">
                                Admin
                            </span>
                        @endif
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-800 transition">
                            Đăng xuất
                        </button>
                    </form>
                </div>
                @else
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-600 hover:text-gray-900 transition">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition">
                        Đăng ký
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile menu panel -->
    <div id="mobile-menu-panel" class="md:hidden hidden bg-white border-t border-gray-100 shadow-lg">
        <div class="px-6 pt-3 pb-4 space-y-3 text-sm">
            <a href="{{ route('home') }}"
               class="block {{ request()->routeIs('home') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600' }}">
                Trang chủ
            </a>
            <a href="{{ route('course.index') }}"
               class="block {{ request()->routeIs('course.index') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600' }}">
                Tổng hợp N
            </a>
            @auth
            <a href="{{ route('user.dashboard') }}"
               class="block {{ request()->routeIs('user.dashboard') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600' }}">
                Dashboard
            </a>
            @endauth
            <a href="{{ route('minna.index') }}"
               class="block {{ request()->routeIs('minna.*') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600' }}">
                Bài học Minna
            </a>
            <a href="{{ route('alphabet.index') }}"
               class="block {{ request()->routeIs('alphabet.index') ? 'text-red-600 font-semibold' : 'text-gray-700 hover:text-red-600' }}">
                Bảng chữ cái
            </a>

            @auth
                <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-gray-100">
                    @csrf
                    <button type="submit"
                            class="w-full text-left text-gray-700 hover:text-red-600 mt-2">
                        Đăng xuất
                    </button>
                </form>
            @else
                <div class="pt-2 border-t border-gray-100 space-y-2">
                    <a href="{{ route('login') }}"
                       class="block py-2 text-gray-600 hover:text-gray-900">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}"
                       class="block bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition text-center">
                        Đăng ký
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('mobile-menu-toggle');
            const panel = document.getElementById('mobile-menu-panel');
            if (!toggle || !panel) return;

            toggle.addEventListener('click', function () {
                panel.classList.toggle('hidden');
            });
        });
    </script>
</header>
