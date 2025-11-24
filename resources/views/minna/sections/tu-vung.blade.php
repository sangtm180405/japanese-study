@if(isset($content['vocab']) && is_array($content['vocab']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Từ vựng</h3>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-4 px-4" style="scrollbar-width: thin; scrollbar-color: #dc2626 #f1f1f1;">
            <style>
                .vocab-table-scroll::-webkit-scrollbar {
                    height: 8px;
                }
                .vocab-table-scroll::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }
                .vocab-table-scroll::-webkit-scrollbar-thumb {
                    background: #dc2626;
                    border-radius: 10px;
                }
                .vocab-table-scroll::-webkit-scrollbar-thumb:hover {
                    background: #b91c1c;
                }
            </style>
            <div class="vocab-table-scroll">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">Từ vựng</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">Hán tự</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider whitespace-nowrap">Âm Hán</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nghĩa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($content['vocab'] as $vocab)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <span class="japanese-text text-base md:text-lg">{{ $vocab['tu_vung'] ?? '' }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <span class="japanese-text">{{ $vocab['han_tu'] ?? '-' }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $vocab['am_han'] ?? '-' }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-sm text-gray-900">
                                    {{ $vocab['nghia'] ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach($content['vocab'] as $vocab)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex-1">
                            <div class="japanese-text text-lg font-semibold text-gray-900 mb-1">
                                {{ $vocab['tu_vung'] ?? '' }}
                            </div>
                            @if(!empty($vocab['han_tu']) && $vocab['han_tu'] !== '-')
                                <div class="japanese-text text-base text-gray-700 mb-1">
                                    {{ $vocab['han_tu'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-1 text-sm">
                        @if(!empty($vocab['am_han']) && $vocab['am_han'] !== '-')
                            <div class="text-gray-600">
                                <span class="font-medium">Âm Hán:</span> {{ $vocab['am_han'] }}
                            </div>
                        @endif
                        <div class="text-gray-900 font-medium">
                            <span class="font-semibold">Nghĩa:</span> {{ $vocab['nghia'] ?? '' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($content['mau_cau']) && is_array($content['mau_cau']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Mẫu câu</h3>
        <div class="space-y-4">
            @foreach($content['mau_cau'] as $mau)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="japanese-text text-lg mb-2">{{ $mau['jp'] ?? '' }}</div>
                    <div class="text-gray-700">{{ $mau['nghia'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($content['countries']) && is_array($content['countries']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Tên nước</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($content['countries'] as $country)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="japanese-text text-lg mb-1">{{ $country['tu_vung'] ?? '' }}</div>
                    <div class="text-gray-700">{{ $country['nghia'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($content['proper_nouns']) && is_array($content['proper_nouns']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Danh từ riêng</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($content['proper_nouns'] as $noun)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="japanese-text text-lg mb-1">{{ $noun['tu_vung'] ?? '' }}</div>
                    <div class="text-gray-700">{{ $noun['nghia'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($content['cau']) && is_array($content['cau']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Câu</h3>
        <div class="space-y-4">
            @foreach($content['cau'] as $cau)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="japanese-text text-lg mb-2">{{ $cau['jp'] ?? '' }}</div>
                    <div class="text-gray-700">{{ $cau['nghia'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($content['places']) && is_array($content['places']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Địa danh</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($content['places'] as $place)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="japanese-text text-lg mb-1">{{ $place['tu_vung'] ?? '' }}</div>
                    <div class="text-gray-700">{{ $place['nghia'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(isset($content['rail']) && is_array($content['rail']))
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Từ vựng về tàu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($content['rail'] as $rail)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="japanese-text text-lg mb-1">{{ $rail['tu_vung'] ?? '' }}</div>
                    <div class="text-gray-700">{{ $rail['nghia'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

