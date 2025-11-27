@extends('adminlayout.app')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý Khóa học JLPT (N5)</h1>
        <a href="{{ route('admin.course-data.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Thêm dữ liệu mới
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Loại section</label>
            <select name="section_type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Tất cả</option>
                <option value="speed_master_n5" {{ request('section_type') == 'speed_master_n5' ? 'selected' : '' }}>Speed Master N5</option>
                <option value="luyen_doc" {{ request('section_type') == 'luyen_doc' ? 'selected' : '' }}>Luyện đọc</option>
                <option value="marugoto_n5" {{ request('section_type') == 'marugoto_n5' ? 'selected' : '' }}>Marugoto N5</option>
                <option value="korede_daijoubu" {{ request('section_type') == 'korede_daijoubu' ? 'selected' : '' }}>Korede Daijoubu</option>
                <option value="gokaku_dekiru" {{ request('section_type') == 'gokaku_dekiru' ? 'selected' : '' }}>Gokaku Dekiru</option>
                <option value="tanki_master_n5" {{ request('section_type') == 'tanki_master_n5' ? 'selected' : '' }}>Tanki Master N5</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Section Key</label>
            <select name="section_key" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Tất cả</option>
                <option value="tuVung" {{ request('section_key') == 'tuVung' ? 'selected' : '' }}>Từ vựng</option>
                <option value="nguPhap" {{ request('section_key') == 'nguPhap' ? 'selected' : '' }}>Ngữ pháp</option>
                <option value="docHieu" {{ request('section_key') == 'docHieu' ? 'selected' : '' }}>Đọc hiểu</option>
                <option value="ngheHieu" {{ request('section_key') == 'ngheHieu' ? 'selected' : '' }}>Nghe hiểu</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Tiêu đề hoặc bài..." 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mr-2">
                Lọc
            </button>
            <a href="{{ route('admin.course-data.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Course Data Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bài</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thứ tự</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($courseData as $data)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ $data->section_type }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600">{{ $data->section_key ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $data->bai ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ $data->title ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-600">{{ $data->order }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.course-data.edit', $data->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                        <form action="{{ route('admin.course-data.destroy', $data->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Bạn có chắc muốn xóa dữ liệu này?')">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <div class="text-lg">Chưa có dữ liệu nào</div>
                    <div class="text-sm mt-2">Hãy thêm dữ liệu đầu tiên!</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($courseData->hasPages())
<div class="mt-6">
    {{ $courseData->appends(request()->query())->links() }}
</div>
@endif
@endsection

