<!-- Sidebar -->
<div class="w-64 bg-gray-900 min-h-screen text-white">
    <div class="p-6">
        <h1 class="text-2xl font-bold">🇯🇵 日本語 Admin</h1>
        <p class="text-gray-400 text-sm mt-1">Hệ thống quản lý</p>
    </div>
    
    <nav class="mt-8">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">📊</span>
            Dashboard
        </a>
        
        <a href="{{ route('admin.alphabets.index') }}" 
           class="flex items-center px-6 py-3 {{ request()->routeIs('admin.alphabets.*') ? 'bg-red-600 border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">🔤</span>
            Quản lý bảng chữ cái
        </a>
        
        <a href="{{ route('admin.kanjis.index') }}" 
           class="flex items-center px-6 py-3 {{ request()->routeIs('admin.kanjis.*') ? 'bg-red-600 border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">🈶</span>
            Quản lý Kanji
        </a>
        
        <a href="{{ route('admin.minna.index') }}" 
           class="flex items-center px-6 py-3 {{ request()->routeIs('admin.minna.*') ? 'bg-red-600 border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">📚</span>
            Quản lý Minna no Nihongo
        </a>
        
        <a href="{{ route('admin.course-data.index') }}" 
           class="flex items-center px-6 py-3 {{ request()->routeIs('admin.course-data.*') ? 'bg-red-600 border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">🎯</span>
            Quản lý Khóa học JLPT
        </a>
        
        <a href="{{ route('admin.users.index') }}" 
           class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-red-600 border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">👥</span>
            Quản lý Users
        </a>
    </nav>
</div>
