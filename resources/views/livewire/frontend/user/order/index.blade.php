<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">My Orders</flux:heading>
        <flux:subheading size="lg" class="mb-6">View and track all your recent orders</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-12 w-full md:w-auto">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Order Placed</p>
                            <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Amount</p>
                            <p class="text-sm font-bold text-slate-900">₹{{ number_format($order->order_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ship To</p>
                            <p class="text-sm font-bold text-sky-600 hover:text-sky-700 cursor-pointer truncate max-w-[120px]">{{ $order->shipping_name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Order Number</p>
                            <p class="text-sm font-bold text-slate-900">{{ $order->order_number }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.orders.details', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-sky-50 text-sky-700 text-sm font-bold rounded-xl hover:bg-sky-100 transition-colors">
                            View Details
                        </a>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @php
                            $status = strtolower($order->status);
                            $statusConfig = match($status) {
                                'delivered' => [
                                    'color' => 'emerald', 
                                    'icon' => 'check-circle', 
                                    'title' => 'Delivered', 
                                    'desc' => 'Your order has been delivered'
                                ],
                                'shipped' => [
                                    'color' => 'indigo', 
                                    'icon' => 'truck', 
                                    'title' => 'Shipped', 
                                    'desc' => 'Your order is on the way'
                                ],
                                'cancelled' => [
                                    'color' => 'red', 
                                    'icon' => 'x-circle', 
                                    'title' => 'Cancelled', 
                                    'desc' => 'This order was cancelled'
                                ],
                                'pending' => [
                                    'color' => 'amber', 
                                    'icon' => 'clock', 
                                    'title' => 'Pending', 
                                    'desc' => 'Awaiting confirmation'
                                ],
                                'processing' => [
                                    'color' => 'sky', 
                                    'icon' => 'arrow-path', 
                                    'title' => 'Processing', 
                                    'desc' => 'We are preparing your order'
                                ],
                                default => [
                                    'color' => 'slate', 
                                    'icon' => 'information-circle', 
                                    'title' => ucfirst($order->status), 
                                    'desc' => 'Status: ' . $order->status
                                ]
                            };
                        @endphp
                        <div class="w-10 h-10 rounded-full bg-{{ $statusConfig['color'] }}-50 flex items-center justify-center border border-{{ $statusConfig['color'] }}-100">
                            <flux:icon name="{{ $statusConfig['icon'] }}" class="size-5 text-{{ $statusConfig['color'] }}-600" />
                        </div>
                        <div>
                            <p class="text-sm font-black text-{{ $statusConfig['color'] }}-700 uppercase tracking-tight">{{ $statusConfig['title'] }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ $statusConfig['desc'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <flux:icon name="shopping-bag" class="size-10 text-slate-300" />
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tighter">No Orders Yet</h3>
                <p class="text-slate-500 mb-6">Looks like you haven't made your first order.</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3 bg-sky-600 text-white font-black text-sm rounded-xl hover:bg-sky-700 transition-colors shadow-lg shadow-sky-100 uppercase tracking-widest active:scale-95">
                    Start Shopping
                </a>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>
</section>