@extends('adminlayout.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Sửa User</h1>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            ← Quay lại
        </a>
    </div>
</div>

@if($user->isLocked())
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-center justify-between">
        <div>
            <span class="font-medium text-amber-800">Tài khoản đang bị khóa</span>
            @if($user->locked_reason)
                <p class="text-sm text-amber-700 mt-1">{{ $user->locked_reason }}</p>
            @endif
        </div>
        @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.unlock', $user) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700">Mở khóa</button>
            </form>
        @endif
    </div>
@elseif($user->id !== auth()->id())
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between">
        <span class="text-gray-700">Tài khoản đang hoạt động.</span>
        <form action="{{ route('admin.users.lock', $user) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700"
                    onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?')">Khóa tài khoản</button>
        </form>
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tên -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Vai trò -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Vai trò *</label>
                <select id="role" name="role" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               @error('role') border-red-500 @enderror" required>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Hủy
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Cập nhật User
            </button>
        </div>
    </form>
</div>
@endsection

