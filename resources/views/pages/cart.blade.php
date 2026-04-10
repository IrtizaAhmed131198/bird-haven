@extends('layouts.app')

@section('title', 'Your Cart | Bird Haven')

@section('content')

    {{-- ===== DESKTOP LAYOUT ===== --}}
    <main class="hidden md:block pt-32 pb-24 px-12 max-w-[1440px] mx-auto">
        <header class="mb-16">
            <div class="flex items-center gap-4 text-sm text-on-surface-variant mb-4 tracking-widest uppercase">
                <a href="{{ route('home') }}">Sanctuary</a>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="text-primary font-bold">Shopping Bag</span>
            </div>
            <h1 class="text-5xl font-extrabold tracking-tighter text-on-surface">Your Sanctuary Selections</h1>
        </header>

        <div class="grid grid-cols-12 gap-12 items-start">
            {{-- Cart Items --}}
            <div class="col-span-12 lg:col-span-8 space-y-8">
                @forelse ($cartItems as $item)
                    @php
                        $product  = $item->product ?? $item->bird ?? $item->accessory;
                        $isBird   = isset($item->bird) && $item->bird !== null;
                        $name     = $product?->name ?? 'Product';
                        $imgUrl   = $product?->image_url ?? asset('assets/images/default.png');
                        $subtitle = $isBird ? ($item->bird->subtitle ?? '') : ($item->accessory->type_label ?? '');
                        $price    = $product?->price ?? 0;
                        $itemId   = $item->id;
                    @endphp
                    <div class="group flex items-center gap-8 p-8 bg-surface-container-lowest rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-primary/5">
                        <div class="w-48 h-48 flex-shrink-0 bg-surface-container rounded-lg overflow-hidden">
                            <img alt="{{ $name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                src="{{ $imgUrl }}" onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="text-[10px] uppercase tracking-widest text-secondary font-bold mb-1 block">
                                        {{ $isBird ? ($item->bird->category->name ?? 'Species') : 'Accessory' }}
                                    </span>
                                    <h3 class="text-2xl font-bold text-on-surface">{{ $name }}</h3>
                                </div>
                                <form action="{{ route('cart.remove', $itemId) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="material-symbols-outlined text-on-surface-variant hover:text-error transition-colors">delete_outline</button>
                                </form>
                            </div>
                            <p class="text-on-surface-variant text-sm mb-6 max-w-md leading-relaxed">{{ $subtitle }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 bg-surface-container-low rounded-full px-4 py-2 border border-outline-variant/10">
                                    <form action="{{ route('cart.update', $itemId) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}" />
                                        <button type="submit" class="w-8 h-8 rounded-full bg-surface-container-lowest flex items-center justify-center hover:bg-primary-container transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                    </form>
                                    <span class="font-bold w-4 text-center">{{ $item->quantity }}</span>
                                    <form action="{{ route('cart.update', $itemId) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}" />
                                        <button type="submit" class="w-8 h-8 rounded-full bg-surface-container-lowest flex items-center justify-center hover:bg-primary-container transition-colors shadow-sm">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </form>
                                </div>
                                <span class="text-2xl font-bold tracking-tight text-primary">${{ number_format($price * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <span class="material-symbols-outlined text-6xl text-outline-variant mb-4 block">shopping_bag</span>
                        <h3 class="text-2xl font-bold mb-2">Your cart is empty</h3>
                        <div class="flex items-center justify-center gap-4 mt-4">
                            <a href="{{ route('shop') }}" class="text-primary font-bold hover:underline">Explore birds</a>
                            <span class="text-on-surface-variant">·</span>
                            <a href="{{ route('accessories') }}" class="text-primary font-bold hover:underline">Browse accessories</a>
                        </div>
                    </div>
                @endforelse

                <div class="pt-8 border-t border-surface-container-high flex items-center gap-6">
                    <a class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium" href="{{ route('shop') }}">
                        <span class="material-symbols-outlined">arrow_back</span> Continue Exploring
                    </a>
                    <a class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-medium" href="{{ route('accessories') }}">
                        <span class="material-symbols-outlined">storefront</span> Add Accessories
                    </a>
                </div>
            </div>

            {{-- Order Summary Sidebar --}}
            <aside class="col-span-12 lg:col-span-4 sticky top-32">
                <div class="bg-surface-container-low rounded-lg p-10 space-y-8">
                    <h2 class="text-3xl font-bold tracking-tight">Order Summary</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Subtotal</span><span class="font-bold text-on-surface">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Insured Shipping</span><span class="font-bold text-on-surface">${{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Sanctuary Tax</span><span class="font-bold text-on-surface">${{ number_format($tax, 2) }}</span>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-outline-variant/20 flex justify-between items-baseline">
                        <span class="text-lg font-bold">Total</span>
                        <div class="text-right">
                            <span class="block text-4xl font-extrabold text-primary tracking-tighter">${{ number_format($total, 2) }}</span>
                            <span class="text-[10px] text-on-surface-variant uppercase tracking-widest mt-1 block">Taxes &amp; Insurance Included</span>
                        </div>
                    </div>
                    <div class="space-y-4 pt-4">
                        <a href="{{ route('checkout') }}" class="block w-full bg-gradient-to-br from-primary to-primary-container text-on-primary py-6 rounded-full font-bold text-lg shadow-lg hover:-translate-y-0.5 transition-transform duration-300 shadow-primary/20 text-center">
                            Complete Adoption
                        </a>
                        <p class="text-center text-xs text-on-surface-variant px-8">
                            By proceeding, you agree to our <a class="underline" href="{{ route('ethical.care') }}">Ethical Care Agreement</a> and Shipping Policies.
                        </p>
                    </div>
                    <div class="pt-8 flex flex-col gap-6">
                        <div class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-full">verified</span>
                            <div>
                                <h4 class="text-sm font-bold">Ethical Guarantee</h4>
                                <p class="text-xs text-on-surface-variant">Every purchase supports our conservation efforts and sanctuary maintenance.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-full">flight_takeoff</span>
                            <div>
                                <h4 class="text-sm font-bold">Climate-Controlled Shipping</h4>
                                <p class="text-xs text-on-surface-variant">Expert handlers ensure safety and comfort during specialized transit.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <div class="md:hidden mt-20 px-6 pb-56">
        <section class="mb-10 pt-4">
            <span class="uppercase tracking-widest text-[10px] font-bold text-primary mb-2 block">Sanctuary Selection</span>
            <h2 class="font-headline text-4xl font-extrabold tracking-tight leading-tight">Your Cart</h2>
            <p class="text-on-surface-variant text-sm mt-2 font-medium">{{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }} ready for shipment.</p>
        </section>

        <div class="flex flex-col gap-6">
            @foreach ($cartItems as $item)
                @php
                    $product = $item->product ?? $item->bird ?? $item->accessory;
                    $isBird  = isset($item->bird) && $item->bird !== null;
                    $name    = $product?->name ?? 'Product';
                    $imgUrl  = $product?->image_url ?? asset('assets/images/default.png');
                    $price   = $product?->price ?? 0;
                    $itemId  = $item->id;
                @endphp
                <div class="bg-surface-container-lowest rounded-xl p-4 flex gap-4 shadow-sm overflow-hidden">
                    <div class="w-24 h-24 bg-surface-container-low rounded-lg overflow-hidden flex-shrink-0">
                        <img alt="{{ $name }}" class="w-full h-full object-cover"
                            src="{{ $imgUrl }}" onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest text-secondary font-bold">{{ $isBird ? 'Bird' : 'Accessory' }}</span>
                                    <h3 class="font-headline font-bold text-base leading-tight">{{ $name }}</h3>
                                </div>
                                <form action="{{ route('cart.remove', $itemId) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-error/80 p-1"><span class="material-symbols-outlined text-xl">delete</span></button>
                                </form>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <span class="font-bold text-primary text-lg font-headline">${{ number_format($price * $item->quantity, 2) }}</span>
                            <div class="flex items-center bg-surface-container rounded-full px-2 py-1 gap-3">
                                <form action="{{ route('cart.update', $itemId) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}" />
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-surface-container-lowest rounded-full shadow-sm active:scale-95 transition-transform">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>
                                </form>
                                <span class="font-bold text-sm">{{ $item->quantity }}</span>
                                <form action="{{ route('cart.update', $itemId) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}" />
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-surface-container-lowest rounded-full shadow-sm active:scale-95 transition-transform">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <section class="mt-12 p-6 bg-surface-container-low rounded-xl">
            <h4 class="font-headline font-bold text-lg mb-4">Summary</h4>
            <div class="flex flex-col gap-3">
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant">Subtotal</span><span class="font-semibold text-on-surface">${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant">Shipping (Insured)</span><span class="font-semibold text-secondary">Free</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant">Estimated Tax</span><span class="font-semibold text-on-surface">${{ number_format($tax, 2) }}</span>
                </div>
            </div>
        </section>
    </div>

    {{-- Mobile Sticky Checkout Bar --}}
    <div class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-white/95 backdrop-blur-2xl rounded-t-[3rem] shadow-[0_-10px_30px_rgba(0,0,0,0.08)] px-6 pb-8 pt-6">
        <div class="flex justify-between items-center mb-4 px-2">
            <div>
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Total Amount</span>
                <p class="text-2xl font-black font-headline text-on-surface">${{ number_format($total, 2) }}</p>
            </div>
            <span class="text-[10px] text-secondary font-bold bg-secondary-container px-2 py-0.5 rounded-full">ETHICAL CHECKOUT</span>
        </div>
        <a href="{{ route('checkout') }}" class="block w-full py-5 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-lg rounded-full shadow-[0_10px_20px_rgba(0,101,139,0.3)] text-center">
            Secure Checkout
        </a>
    </div>

@endsection
