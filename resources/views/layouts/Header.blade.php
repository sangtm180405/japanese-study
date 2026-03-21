<!-- Header -->
<header class="fixed w-full top-0 bg-white/95 backdrop-blur-md shadow-md border-b border-gray-200/50 z-50 transition-all duration-300" id="main-header">
    <nav class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo Section -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group flex-shrink-0">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-600 to-red-700 rounded-xl blur opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <img src="{{ asset('images/logo/yamato.jpg') }}"
                         alt="Logo"
                         class="relative w-14 h-14 rounded-xl object-cover ring-2 ring-white shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                </div>
                <div class="hidden md:block">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent group-hover:from-red-600 group-hover:to-red-700 transition-all duration-300">
                        日本語
                    </h1>
                    <p class="text-xs text-gray-500 font-medium">Học tiếng Nhật hiệu quả</p>
                </div>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Trang chủ
                    </span>
                    @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
                <a href="{{ route('course.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('course.index') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Tổng hợp N
                    </span>
                    @if(request()->routeIs('course.index'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
                @auth
                @endauth
                <a href="{{ route('minna.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('minna.*') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Bài học Minna
                    </span>
                    @if(request()->routeIs('minna.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
                <a href="{{ route('vocabulary.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('vocabulary.*') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Từ vựng
                    </span>
                    @if(request()->routeIs('vocabulary.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
                <a href="{{ route('flashcard.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('flashcard.*') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Flashcard
                    </span>
                    @if(request()->routeIs('flashcard.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
                <a href="{{ route('alphabet.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('alphabet.index') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Bảng chữ cái
                    </span>
                    @if(request()->routeIs('alphabet.index'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
                <a href="{{ route('kanji.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 group">
                    <span class="relative z-10 {{ request()->routeIs('kanji.*') ? 'text-red-600' : 'text-gray-700 group-hover:text-gray-900' }}">
                        Ôn Kanji
                    </span>
                    @if(request()->routeIs('kanji.*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full"></span>
                    @else
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-red-600 to-red-700 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></span>
                    @endif
                </a>
            </div>
            
            <!-- Right Section -->
            <div class="flex items-center space-x-3">
                @auth
                    <!-- User Menu Desktop - Icon tài khoản -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="{{ route('user.dashboard') }}" title="Tài khoản - {{ auth()->user()->name }}" class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 ring-2 ring-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-all duration-300 shadow-md">
                                Admin
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-gradient-to-r from-gray-900 to-gray-800 text-white text-sm font-semibold rounded-lg hover:from-gray-800 hover:to-gray-700 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Auth Buttons Desktop -->
                    <div class="hidden lg:flex items-center space-x-3">
                        <a href="{{ route('login') }}"
                           class="px-5 py-2.5 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors duration-300">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-lg">
                            Đăng ký
                        </a>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <button type="button"
                        id="mobile-menu-toggle"
                        class="lg:hidden inline-flex items-center justify-center w-10 h-10 text-gray-700 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-300 touch-manipulation disabled:opacity-60 disabled:pointer-events-none">
                    <span class="sr-only">Mở menu</span>
                    <svg id="menu-icon" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="4" y1="7" x2="20" y2="7"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="17" x2="20" y2="17"></line>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile menu panel: overflow-hidden + max-height transition để transitionend ổn định, tránh khựng khi spam nút menu -->
    <div id="mobile-menu-panel" class="lg:hidden hidden overflow-hidden bg-white border-t border-gray-200 shadow-xl transition-[max-height] duration-300 ease-out motion-reduce:transition-none">
        <div class="px-4 pt-4 pb-6 space-y-1">
            <a href="{{ route('home') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('home') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Trang chủ
            </a>
            <a href="{{ route('course.index') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('course.index') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Tổng hợp N
            </a>
            @auth
            @endauth
            <a href="{{ route('minna.index') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('minna.*') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Bài học Minna
            </a>
            <a href="{{ route('vocabulary.index') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('vocabulary.*') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Từ vựng
            </a>
            <a href="{{ route('flashcard.index') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('flashcard.*') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                Flashcard
            </a>
            <a href="{{ route('alphabet.index') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('alphabet.index') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Bảng chữ cái
            </a>
            <a href="{{ route('kanji.index') }}"
               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition-all duration-300 {{ request()->routeIs('kanji.*') ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Ôn Kanji
            </a>

            @auth
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 mb-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                @if(auth()->user()->role === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gradient-to-r from-red-100 to-red-50 text-red-700 border border-red-200 mt-1">
                                        Admin
                                    </span>
                                @else
                                    <p class="text-xs text-gray-500">Thành viên</p>
                                @endif
                            </div>
                        </div>
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="block px-4 py-3 mb-3 bg-red-600 hover:bg-red-700 text-white text-center text-sm font-semibold rounded-lg transition-all duration-300">
                            Admin
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white text-sm font-semibold rounded-lg hover:from-gray-800 hover:to-gray-700 transition-all duration-300 shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-4 mt-4 border-t border-gray-200 space-y-2">
                    <a href="{{ route('login') }}"
                       class="block px-4 py-3 text-center text-gray-700 font-semibold hover:bg-gray-50 rounded-lg transition-colors duration-300">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}"
                       class="block px-4 py-3 text-center bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md">
                        Đăng ký
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<!-- Spacer for fixed header -->
<div class="h-20"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('mobile-menu-toggle');
        const panel = document.getElementById('mobile-menu-panel');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        if (!toggle || !panel) return;

        let mobileMenuOpen = false;
        let mobileMenuBusy = false;
        let mobileMenuFallbackTimer = null;

        function clearMobileMenuFallback() {
            if (mobileMenuFallbackTimer !== null) {
                clearTimeout(mobileMenuFallbackTimer);
                mobileMenuFallbackTimer = null;
            }
        }

        function setMobileMenuBusy(busy) {
            mobileMenuBusy = busy;
            toggle.disabled = busy;
            toggle.setAttribute('aria-busy', busy ? 'true' : 'false');
        }

        function onMobilePanelTransitionEnd(e) {
            if (e.target !== panel || e.propertyName !== 'max-height') {
                return;
            }
            clearMobileMenuFallback();
            setMobileMenuBusy(false);
            if (!mobileMenuOpen) {
                panel.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }

        panel.addEventListener('transitionend', onMobilePanelTransitionEnd);

        toggle.addEventListener('click', function () {
            if (mobileMenuBusy) {
                return;
            }

            clearMobileMenuFallback();
            setMobileMenuBusy(true);

            if (!mobileMenuOpen) {
                mobileMenuOpen = true;
                panel.classList.remove('hidden');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                panel.style.maxHeight = '0px';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        panel.style.maxHeight = panel.scrollHeight + 'px';
                    });
                });
            } else {
                mobileMenuOpen = false;
                panel.style.maxHeight = '0px';
            }

            // Một số trình duyệt không bắn transitionend (hoặc user bật reduce motion) — vẫn mở khóa sau 400ms
            mobileMenuFallbackTimer = window.setTimeout(function () {
                mobileMenuFallbackTimer = null;
                if (!mobileMenuBusy) {
                    return;
                }
                setMobileMenuBusy(false);
                if (!mobileMenuOpen) {
                    panel.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            }, 400);
        });

        // Header scroll effect
        let lastScroll = 0;
        const header = document.getElementById('main-header');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                header.classList.add('shadow-lg');
                header.classList.remove('shadow-md');
            } else {
                header.classList.remove('shadow-lg');
                header.classList.add('shadow-md');
            }
            
            lastScroll = currentScroll;
        });
    });

    // Chặn chuột phải và các phím tắt DevTools phổ biến + ghi log / khóa tài khoản khi vi phạm
    document.addEventListener('contextmenu', function (event) {
        event.preventDefault();
    });

    @auth
    window.__devtoolsReport = {
        url: @json(route('devtools.violation')),
        csrf: @json(csrf_token()),
        report: function (violationType) {
            fetch(this.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ violation_type: violationType })
            }).then(function (r) {
                if (r.status === 200) {
                    r.json().then(function (data) {
                        if (data && data.locked) window.location.reload();
                    }).catch(function () {});
                } else if (r.status === 403) {
                    window.location.reload();
                }
            }).catch(function () {});
        }
    };
    @endauth

    document.addEventListener('keydown', function (event) {
        const key = event.key || '';
        var violationType = null;

        if (key === 'F12' || event.keyCode === 123) {
            violationType = 'f12';
        } else if (event.ctrlKey && event.shiftKey && (key.toLowerCase() === 'i' || event.keyCode === 73)) {
            violationType = 'ctrl_shift_i';
        } else if (event.ctrlKey && event.shiftKey && (key.toLowerCase() === 'j' || event.keyCode === 74)) {
            violationType = 'ctrl_shift_j';
        } else if (event.ctrlKey && (key.toLowerCase() === 'u' || event.keyCode === 85)) {
            violationType = 'ctrl_u';
        }

        if (violationType) {
            event.preventDefault();
            if (window.__devtoolsReport) window.__devtoolsReport.report(violationType);
            return false;
        }
    });
</script>
