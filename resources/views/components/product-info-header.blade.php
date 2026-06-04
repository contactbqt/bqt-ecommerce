@props(['product'])

<div class="flex items-start gap-6 mb-8 pb-8 border-b border-gray-100/80">
    <div class="flex-shrink-0">
        @if(!empty($product->image))
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-28 h-28 object-cover rounded-2xl shadow-sm border border-gray-100">
        @else
            <img src="{{ asset('assets/images/no_image.jpg') }}" alt="No Image" class="w-28 h-28 object-cover rounded-2xl shadow-sm border border-gray-100">
        @endif
    </div>

    <div class="flex flex-col justify-center h-28">
        <h4 class="text-2xl font-bold text-slate-800 leading-tight mb-2 tracking-tight">{{ ucwords(strtolower($product->product_name)) }}</h4>
        
        <div class="flex items-center">
            @php
                $isActive = $product->status == 1;
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black tracking-wider {{ $isActive ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                {{ $isActive ? 'Active' : 'Inactive' }}
            </span>
        </div>

        @if($slot->isNotEmpty())
            <div class="mt-3 text-sm text-slate-500 font-medium leading-relaxed max-w-2xl">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
