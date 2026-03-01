<!-- Header -->
<header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
        <p class="text-gray-500 text-sm">Chào mừng trở lại, Admin!</p>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <a href="{{ route('admin.notifications.index') }}" class="relative inline-flex px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200" title="Thông báo">
                <span>🔔</span>
                @php $unreadCount = \App\Models\Notification::unreadCountFor(auth()->user()); @endphp
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full min-w-[1.25rem] h-5 flex items-center justify-center px-1">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
            </a>
        </div>
        <div class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                A
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Admin</p>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="px-3 py-2 text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                Đăng xuất
            </button>
        </form>
    </div>
</header>
