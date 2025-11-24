<!-- Sidebar -->
<div class="w-64 bg-gray-900 min-h-screen text-white">
    <div class="p-6">
        <h1 class="text-2xl font-bold">🇯🇵 日本語 Admin</h1>
        <p class="text-gray-400 text-sm mt-1">Hệ thống quản lý</p>
    </div>
    
    <nav class="mt-8">
        <a href="#" class="flex items-center px-6 py-3 bg-red-600 border-l-4 border-red-500">
            <span class="mr-3">📊</span>
            Dashboard
        </a>
                <a href="{{ route('admin.alphabets.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <span class="mr-3">🔤</span>
                    Quản lý bảng chữ cái
                </a>
    </nav>
</div>
