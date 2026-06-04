<div class="flex flex-wrap gap-3">
    @if($categoryAttributeValues->isNotEmpty())
        @foreach($categoryAttributeValues as $valueItem)
            <label class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-100 hover:border-gray-300 transition-all">
                <input type="checkbox" 
                       value="{{ $valueItem->id }}" 
                       wire:model="selectedAttributes"
                       class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-colors">
                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $valueItem->value_name }}</span>
            </label>
        @endforeach
    @endif
</div>
