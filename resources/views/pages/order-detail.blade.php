@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' | Bird Haven')

@section('content')

    {{-- ===== DESKTOP MAIN ===== --}}
    <main class="hidden md:block pt-32 pb-24 px-12 max-w-[1280px] mx-auto min-h-screen">

        {{-- Order Header --}}
        <div class="mb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div>
                    <a href="{{ route('orders') }}" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors text-sm mb-4">
                        <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Order History
                    </a>
                    <h1 class="text-5xl font-headline font-extrabold tracking-tighter mb-2">Order Details</h1>
                    <p class="text-on-surface-variant font-medium flex items-center gap-2">
                        Order #{{ $order->order_number }} &bull; Placed on {{ $order->created_at->format('F d, Y') }}
                    </p>
                </div>
                <div class="flex gap-4">
                    <button class="bg-surface-container-high text-on-surface px-6 py-3 rounded-full font-headline font-semibold hover:-translate-y-0.5 transition-transform duration-300">
                        Download Invoice
                    </button>
                    <a href="{{ route('shipping.tracking') }}" class="bg-gradient-to-br from-primary to-primary-container text-white px-8 py-3 rounded-full font-headline font-semibold hover:-translate-y-0.5 transition-transform duration-300 shadow-lg">
                        Track Delivery
                    </a>
                </div>
            </div>

            {{-- Status Banner --}}
            <div class="bg-secondary-container/30 border border-secondary-container/20 rounded-xl p-8 flex items-center gap-6 overflow-hidden relative">
                <div class="absolute -right-12 -top-12 opacity-10 rotate-12">
                    <span class="material-symbols-outlined text-[12rem]">flutter_dash</span>
                </div>
                <div class="bg-secondary text-on-secondary p-4 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">local_shipping</span>
                </div>
                <div>
                    <h2 class="text-xl font-headline font-bold text-on-secondary-container">
                        @if ($order->status === 'transit')
                            Your feathered friend is on the way!
                        @elseif ($order->status === 'delivered' || $order->status === 'arrived')
                            Your order has arrived!
                        @else
                            Order status: {{ ucfirst($order->status) }}
                        @endif
                    </h2>
                    <p class="text-on-secondary-fixed-variant">{{ $order->status_message ?? 'We will keep you updated on the progress.' }}</p>
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Left: Items + Shipping --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Items --}}
                <div class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(25,28,29,0.04)]">
                    <h3 class="text-xl font-headline font-bold mb-8">Items Purchased</h3>
                    <div class="space-y-8">
                        @foreach ($order->items as $item)
                            <div class="flex flex-col md:flex-row gap-6 group">
                                <div class="w-full md:w-32 h-32 bg-surface-container rounded-lg overflow-hidden shrink-0">
                                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        src="{{ $item->bird->image_url }}" alt="{{ $item->bird->name }}" />
                                </div>
                                <div class="flex-grow flex flex-col justify-center">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm uppercase tracking-widest text-primary font-bold mb-1">{{ $item->bird->category->name ?? 'Ethical Guardian Pick' }}</p>
                                            <h4 class="text-xl font-headline font-bold">{{ $item->bird->name }}</h4>
                                            <p class="text-on-surface-variant mt-1">{{ $item->bird->subtitle }}</p>
                                            <p class="text-sm text-on-surface-variant mt-1">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <p class="text-xl font-headline font-bold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(25,28,29,0.04)]">
                    <h3 class="text-xl font-headline font-bold mb-6">Shipping Details</h3>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-2">Delivery Address</p>
                            <p class="font-semibold text-on-surface">{{ $order->shipping_name }}</p>
                            <p class="text-on-surface-variant text-sm">{{ $order->shipping_address }}</p>
                            @if ($order->shipping_address2)
                                <p class="text-on-surface-variant text-sm">{{ $order->shipping_address2 }}</p>
                            @endif
                            <p class="text-on-surface-variant text-sm">{{ $order->shipping_city }}, {{ $order->shipping_postal }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-2">Delivery Window</p>
                            <p class="font-semibold text-on-surface">{{ $order->estimated_delivery ?? '5-7 Business Days' }}</p>
                            <p class="text-on-surface-variant text-sm mt-2">Climate-controlled transit with specialist handlers.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(25,28,29,0.04)] sticky top-32">
                    <h3 class="text-xl font-headline font-bold mb-6 pb-4 border-b border-outline-variant/20">Order Summary</h3>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">Subtotal</span>
                            <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">Shipping</span>
                            <span class="font-semibold">${{ number_format($order->shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">Tax</span>
                            <span class="font-semibold">${{ number_format($order->tax, 2) }}</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/20 flex justify-between items-baseline">
                        <span class="font-bold text-lg">Total</span>
                        <span class="text-2xl font-black text-primary font-headline">${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="mt-8 p-4 bg-secondary-container/30 rounded-xl flex gap-3">
                        <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings: 'FILL' 1;">eco</span>
                        <p class="text-xs text-on-secondary-container leading-relaxed">5% of this order supports the <strong>Global Avian Sanctuary Fund</strong>.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ===== MOBILE MAIN ===== --}}
    <div class="md:hidden pt-20 pb-32 px-6">

        {{-- Status Banner --}}
        <div class="mb-6 bg-secondary-container/30 rounded-xl p-6 flex items-center gap-4">
            <span class="material-symbols-outlined text-secondary text-3xl">local_shipping</span>
            <div>
                <p class="font-bold text-on-secondary-container text-sm">{{ ucfirst($order->status) }}</p>
                <p class="text-xs text-on-secondary-fixed-variant">{{ $order->status_message ?? 'In Progress' }}</p>
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm mb-6">
            <h3 class="font-headline font-bold mb-4">Items Purchased</h3>
            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex gap-4">
                        <div class="w-20 h-20 bg-surface-container rounded-lg overflow-hidden shrink-0">
                            <img class="w-full h-full object-cover" src="{{ $item->bird->image_url }}" alt="{{ $item->bird->name }}" />
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm">{{ $item->bird->name }}</p>
                            <p class="text-xs text-on-surface-variant">Qty: {{ $item->quantity }}</p>
                            <p class="font-bold text-primary mt-1">${{ number_format($item->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Total --}}
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm mb-6">
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant">Subtotal</span>
                    <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant">Shipping + Tax</span>
                    <span class="font-semibold">${{ number_format($order->shipping + $order->tax, 2) }}</span>
                </div>
            </div>
            <div class="pt-3 border-t border-outline-variant/20 flex justify-between items-baseline">
                <span class="font-bold">Total</span>
                <span class="text-xl font-black text-primary font-headline">${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <a href="{{ route('shipping.tracking') }}" class="block w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg text-center">
            Track My Order
        </a>
    </div>

@endsection
