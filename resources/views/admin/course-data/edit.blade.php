@extends('adminlayout.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Sửa dữ liệu khóa học</h1>
        <a href="{{ route('admin.course-data.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            ← Quay lại
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.course-data.update', $courseData->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Section Type -->
            <div>
                <label for="section_type" class="block text-sm font-medium text-gray-700 mb-2">Loại section *</label>
                <select id="section_type" name="section_type" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               @error('section_type') border-red-500 @enderror" required>
                    <option value="">Chọn loại</option>
                    <option value="speed_master_n5" {{ old('section_type', $courseData->section_type) == 'speed_master_n5' ? 'selected' : '' }}>Speed Master N5</option>
                    <option value="luyen_doc" {{ old('section_type', $courseData->section_type) == 'luyen_doc' ? 'selected' : '' }}>Luyện đọc</option>
                    <option value="marugoto_n5" {{ old('section_type', $courseData->section_type) == 'marugoto_n5' ? 'selected' : '' }}>Marugoto N5</option>
                    <option value="korede_daijoubu" {{ old('section_type', $courseData->section_type) == 'korede_daijoubu' ? 'selected' : '' }}>Korede Daijoubu</option>
                    <option value="gokaku_dekiru" {{ old('section_type', $courseData->section_type) == 'gokaku_dekiru' ? 'selected' : '' }}>Gokaku Dekiru</option>
                    <option value="tanki_master_n5" {{ old('section_type', $courseData->section_type) == 'tanki_master_n5' ? 'selected' : '' }}>Tanki Master N5</option>
                </select>
                @error('section_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Section Key -->
            <div>
                <label for="section_key" class="block text-sm font-medium text-gray-700 mb-2">Section Key</label>
                <select id="section_key" name="section_key" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               @error('section_key') border-red-500 @enderror">
                    <option value="">Không có</option>
                    <option value="tuVung" {{ old('section_key', $courseData->section_key) == 'tuVung' ? 'selected' : '' }}>Từ vựng</option>
                    <option value="nguPhap" {{ old('section_key', $courseData->section_key) == 'nguPhap' ? 'selected' : '' }}>Ngữ pháp</option>
                    <option value="docHieu" {{ old('section_key', $courseData->section_key) == 'docHieu' ? 'selected' : '' }}>Đọc hiểu</option>
                    <option value="ngheHieu" {{ old('section_key', $courseData->section_key) == 'ngheHieu' ? 'selected' : '' }}>Nghe hiểu</option>
                </select>
                @error('section_key')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Bài -->
            <div>
                <label for="bai" class="block text-sm font-medium text-gray-700 mb-2">Bài</label>
                <input type="text" id="bai" name="bai" value="{{ old('bai', $courseData->bai) }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              @error('bai') border-red-500 @enderror" 
                       placeholder="Bài 1">
                @error('bai')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Tiêu đề -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề</label>
                <input type="text" id="title" name="title" value="{{ old('title', $courseData->title) }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              @error('title') border-red-500 @enderror" 
                       placeholder="Tiêu đề...">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Thứ tự -->
            <div>
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Thứ tự *</label>
                <input type="number" id="order" name="order" value="{{ old('order', $courseData->order) }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2
                              @error('order') border-red-500 @enderror" 
                       placeholder="1" min="0" required>
                @error('order')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Content (JSON) -->
        <div class="mt-6">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Nội dung (JSON) *</label>
            <textarea id="content" name="content" rows="10"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-sm
                             @error('content') border-red-500 @enderror"
                      placeholder='{"key": "value"}' required>{{ old('content', json_encode($courseData->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Nhập dữ liệu dạng JSON hợp lệ</p>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.course-data.index') }}" 
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Hủy
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Cập nhật dữ liệu
            </button>
        </div>
    </form>
</div>
@endsection

