@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number . ' | BirdHaven Admin')

@section('content')

@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
        <p class="text-emerald-700 text-sm font-medium">{{ session('success') }}</p>
    </div>
@endif

{{-- Header --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('admin.orders.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <h1 class="text-3xl font-extrabold text-on-surface font-headline tracking-tight">
                Order #{{ $order->order_number }}
            </h1>
        </div>
        <p class="text-on-surface-variant text-sm ml-9">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
    </div>
    @php
    $statusColors = [
        'delivered' => 'bg-emerald-100 text-emerald-800',
        'arrived'   => 'bg-emerald-100 text-emerald-800',
        'transit'   => 'bg-sky-100 text-sky-800',
        'preparing' => 'bg-amber-100 text-amber-800',
        'cancelled' => 'bg-slate-100 text-slate-600',
    ];
    $sCls = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600';
    @endphp
    <div class="flex items-center gap-3">
        <span class="px-4 py-2 rounded-full text-sm font-bold {{ $sCls }}">{{ ucfirst($order->status) }}</span>
        @if ($order->shipment)
            <a href="{{ route('admin.shipments.edit', $order->shipment->id) }}"
               class="px-4 py-2 bg-sky-50 text-sky-700 border border-sky-200 rounded-full text-sm font-semibold hover:bg-sky-100 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">local_shipping</span>
                Track Shipment
            </a>
        @else
            <a href="{{ route('admin.shipments.create', ['order_id' => $order->id]) }}"
               class="px-4 py-2 bg-primary/10 text-primary border border-primary/20 rounded-full text-sm font-semibold hover:bg-primary/20 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                Create Shipment
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Items + Totals --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Items --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">shopping_bag</span>
                <h2 class="font-bold text-on-surface">Order Items</h2>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach ($order->items as $item)
                @php
                    $product = $item->bird ?? $item->accessory;
                    $imgSrc  = $product?->image
                        ? asset('uploads/images/' . ($item->bird ? 'birds' : 'accessories') . '/' . $product->image)
                        : asset('assets/images/placeholder.png');
                @endphp
                <div class="px-6 py-4 flex items-center gap-4">
                    <img src="{{ $imgSrc }}" alt="" class="w-14 h-14 rounded-lg object-cover shrink-0 bg-slate-100">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-on-surface text-sm truncate">{{ $product?->name ?? 'Product' }}</p>
                        <p class="text-xs text-slate-400">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="font-bold text-on-surface text-sm">${{ number_format($item->price * $item->quantity, 2) }}</p>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-surface-container-low border-t border-slate-100 space-y-2">
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Shipping</span><span>{{ $order->shipping > 0 ? '$'.number_format($order->shipping,2) : 'Free' }}</span>
                </div>
                @if ($order->tax > 0)
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Tax</span><span>${{ number_format($order->tax, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-on-surface pt-2 border-t border-slate-200">
                    <span>Total</span><span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">sync</span>
                <h2 class="font-bold text-on-surface">Update Order Status</h2>
            </div>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface-variant">Status</label>
                        <select name="status"
                            class="w-full bg-surface-container rounded-lg px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-on-surface">
                            @foreach (['preparing' => 'Preparing', 'transit' => 'In Transit', 'arrived' => 'Arrived', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $val => $label)
                                <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface-variant">Status Message (optional)</label>
                        <input name="status_message" type="text" value="{{ $order->status_message }}"
                            placeholder="e.g. Package out for delivery"
                            class="w-full bg-surface-container rounded-lg px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-on-surface text-sm" />
                    </div>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white rounded-lg font-semibold text-sm hover:opacity-90 transition-opacity">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Update Payment --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">payments</span>
                <h2 class="font-bold text-on-surface">Update Payment Status</h2>
            </div>
            <form action="{{ route('admin.orders.payment', $order) }}" method="POST" class="p-6 flex items-end gap-4">
                @csrf
                @method('PATCH')
                <div class="flex-1 space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Payment Status</label>
                    <select name="payment_status"
                        class="w-full bg-surface-container rounded-lg px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-on-surface">
                        @foreach (['pending' => 'Pending', 'paid' => 'Paid', 'awaiting_verification' => 'Awaiting Verification', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $val => $label)
                            <option value="{{ $val }}" @selected($order->payment_status === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-primary text-white rounded-lg font-semibold text-sm hover:opacity-90 transition-opacity shrink-0">
                    Save
                </button>
            </form>
        </div>
    </div>

    {{-- Right: Customer + Shipping + Payment --}}
    <div class="space-y-6">

        {{-- Customer --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                <h2 class="font-bold text-on-surface">Customer</h2>
            </div>
            <div class="p-6 space-y-3">
                @if ($order->user)
                <div class="flex items-center gap-3">
                    <img src="{{ $order->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <p class="font-semibold text-on-surface text-sm">{{ $order->user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $order->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $order->user) }}"
                    class="inline-flex items-center gap-1 text-primary text-xs font-semibold hover:underline">
                    View profile <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
                @else
                <p class="text-sm text-slate-400">Guest checkout</p>
                @endif
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">location_on</span>
                <h2 class="font-bold text-on-surface">Shipping Address</h2>
            </div>
            <div class="p-6 text-sm text-slate-600 space-y-1 leading-relaxed">
                <p class="font-semibold text-on-surface">{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_address }}</p>
                @if ($order->shipping_address2)
                    <p>{{ $order->shipping_address2 }}</p>
                @endif
                <p>{{ $order->shipping_city }} {{ $order->shipping_postal }}</p>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">credit_card</span>
                <h2 class="font-bold text-on-surface">Payment</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Method</span>
                    <span class="font-semibold text-on-surface capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Status</span>
                    @php
                    $pColor = match($order->payment_status) {
                        'paid'                   => 'text-emerald-700',
                        'awaiting_verification'  => 'text-amber-700',
                        'failed', 'refunded'     => 'text-red-600',
                        default                  => 'text-slate-500',
                    };
                    @endphp
                    <span class="font-semibold {{ $pColor }} capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                </div>
                @if ($order->transaction_id)
                <div class="flex justify-between">
                    <span class="text-slate-500">Transaction ID</span>
                    <span class="font-mono text-xs text-on-surface">{{ $order->transaction_id }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        @if ($order->notes)
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">notes</span>
                <h2 class="font-bold text-on-surface">Order Notes</h2>
            </div>
            <div class="p-6 text-sm text-slate-600 leading-relaxed">{{ $order->notes }}</div>
        </div>
        @endif
    </div>
</div>

@endsection
