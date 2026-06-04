<div>
    <a href="{{ route('cart') }}" class="text-slate-500 hover:text-sky-600 transition-colors relative group flex items-center">
        <div class="p-2 bg-slate-50 rounded-full group-hover:bg-sky-50 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-[11px] font-black text-white flex items-center justify-center border-2 border-white shadow-sm">{{ $cartCount }}</span>
    </a>
</div>
