@extends('layouts.app')

@section('title', 'Order Confirmed | Bird Haven')

@section('content')

    <div class="max-w-[700px] mx-auto py-8 px-4 md:py-16">
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_20px_40px_rgba(25,28,29,0.06)]">

            {{-- Header --}}
            <header class="flex items-center justify-between px-8 py-8 bg-white/80 backdrop-blur-xl">
                <a href="{{ route('home') }}" class="text-2xl font-bold font-headline tracking-tighter text-slate-900">
                    Bird Haven
                </a>
                <div class="text-primary font-semibold text-sm">ORDER #{{ $order->order_number }}</div>
            </header>

            {{-- Hero Section --}}
            <div class="relative px-8">
                <div class="h-64 md:h-80 w-full overflow-hidden rounded-lg">
                    <img alt="Order confirmation banner" class="w-full h-full object-cover"
                        src="{{ asset('assets/images/order-banner.png') }}" />
                </div>
                <div class="mt-8">
                    <h2 class="text-4xl font-extrabold font-headline leading-tight text-on-surface tracking-tight">
                        Your Sanctuary Adoption is Confirmed!
                    </h2>
                    <p class="mt-4 text-on-surface-variant text-lg leading-relaxed">
                        Thank you for joining our mission of ethical avian guardianship. Your contribution directly supports the preservation and care of these majestic species.
                    </p>
                </div>
            </div>

            {{-- Order Summary Bento Grid --}}
            <div class="px-8 mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Adoption Details --}}
                <div class="bg-surface-container-low p-6 rounded-lg">
                    <h3 class="font-headline font-bold text-slate-900 mb-4">Adoption Details</h3>
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-on-surface">{{ $item->bird->name }}</p>
                                    <p class="text-xs text-on-surface-variant">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-bold text-primary">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        @endforeach
                        <div class="flex justify-between items-center border-t border-outline-variant/15 pt-4">
                            <p class="text-on-surface-variant">Sanctuary Tax</p>
                            <p class="font-semibold text-on-surface">${{ number_format($order->tax, 2) }}</p>
                        </div>
                        <div class="flex justify-between items-center border-t border-outline-variant/15 pt-4">
                            <p class="font-bold text-on-surface text-lg">Total Amount</p>
                            <p class="font-black text-primary text-xl font-headline">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Shipping Details --}}
                <div class="bg-surface-container-low p-6 rounded-lg">
                    <h3 class="font-headline font-bold text-slate-900 mb-4">Shipping Address</h3>
                    <div class="text-on-surface-variant space-y-1">
                        <p class="font-semibold text-on-surface">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        @if ($order->shipping_address2)
                            <p>{{ $order->shipping_address2 }}</p>
                        @endif
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_postal }}</p>
                    </div>
                    <div class="mt-6">
                        <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Estimated Arrival</p>
                        <p class="text-on-surface font-semibold">{{ $order->estimated_delivery ?? '5-7 Business Days' }}</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="px-8 py-12 flex flex-col items-center">
                <a href="{{ route('orders') }}"
                    class="bg-gradient-to-r from-primary to-primary-container text-white font-bold py-4 px-10 rounded-full shadow-lg hover:-translate-y-0.5 transition-transform duration-300 inline-flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">local_shipping</span>
                    Track My Journey
                </a>
                <p class="mt-4 text-xs text-on-surface-variant">Track your companion's care package and digital welcome kit.</p>
            </div>

            {{-- Info Block --}}
            <div class="mx-8 mb-12 p-8 bg-secondary-container/30 border border-secondary-container rounded-lg flex gap-4">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">info</span>
                <div>
                    <h4 class="font-bold text-secondary text-sm">Next Steps</h4>
                    <p class="text-on-secondary-container text-sm mt-1">
                        Check your inbox for a follow-up email containing your high-resolution digital adoption certificate and live sanctuary webcam access credentials.
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <footer class="bg-slate-50 border-t border-outline-variant/10 px-8 pt-16 pb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                    <div>
                        <h3 class="text-xl font-black font-headline text-slate-900 tracking-tighter mb-2">Bird Haven</h3>
                        <p class="text-sm text-slate-500 max-w-xs leading-relaxed">
                            Dedicated to the preservation, documentation, and ethical companionship of rare avian species across the globe.
                        </p>
                    </div>
                    <div class="flex space-x-6 md:justify-end">
                        <a href="#" class="text-slate-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">public</span>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">flutter_dash</span>
                        </a>
                    </div>
                </div>
                <div class="mt-12 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sky-800 text-xs font-semibold tracking-wide">Ethical Avian Guardianship.</div>
                    <div class="flex gap-6 text-slate-400 text-xs font-medium">
                        <a class="hover:text-primary" href="#">Privacy Policy</a>
                        <a class="hover:text-primary" href="#">Ethical Sourcing</a>
                        <a class="hover:text-primary" href="{{ route('contact') }}">Contact</a>
                    </div>
                </div>
                <div class="mt-8 text-center text-[10px] text-slate-400 uppercase tracking-[0.2em]">
                    © {{ date('Y') }} Bird Haven. All Rights Reserved.
                </div>
            </footer>
        </div>
    </div>

@endsection
