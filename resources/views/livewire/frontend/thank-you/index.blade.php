<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center max-w-2xl mx-auto shadow-sm">
        <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 border-8 border-emerald-100">
            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-black text-slate-900 mb-4">Thank You!</h1>
        <p class="text-lg text-slate-600 mb-2">Your order has been successfully placed.</p>
        
        @if($orderId)
            <div class="inline-block bg-slate-50 border border-slate-200 rounded-lg px-6 py-3 mt-4 mb-8">
                <span class="text-sm text-slate-500 uppercase tracking-wider font-bold block mb-1">Order Number</span>
                <span class="text-2xl font-black text-sky-600">{{ $order_no }}</span>
            </div>
        @endif

        <p class="text-slate-500 mb-8 max-w-md mx-auto">We've sent a confirmation email with your order details. You can track your order status in your account.</p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 text-white font-black px-8 py-3.5 rounded-xl hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200 active:scale-95">
                Back to Home
            </a>
            @if($orderId)
            <a href="{{ route('user.orders.details', ['id' => $orderId]) }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-sky-600 text-white font-black px-8 py-3.5 rounded-xl hover:bg-sky-500 transition-colors shadow-lg shadow-sky-100 active:scale-95">
                Order Details
            </a>
            @else
            <a href="{{ route('shop') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-sky-600 text-white font-black px-8 py-3.5 rounded-xl hover:bg-sky-500 transition-colors shadow-lg shadow-sky-100 active:scale-95">
                Continue Shopping
            </a>
            @endif
        </div>
    </div>
</div>
