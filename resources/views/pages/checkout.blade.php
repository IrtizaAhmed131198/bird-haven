@extends('layouts.checkout')

@section('title', 'Checkout | Bird Haven')

@push('styles')
<style>
    .payment-card {
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color .2s, background .2s;
    }
    .payment-card.selected {
        border-color: #0c6780;
        background: #f0faff;
    }
    .payment-details { display: none; }
    .payment-details.active { display: block; }
</style>
@endpush

@section('content')

    {{-- ===== DESKTOP LAYOUT ===== --}}
    <main class="hidden md:flex pt-32 pb-24 px-12 max-w-[1440px] mx-auto min-h-screen flex-col md:flex-row gap-16">

        {{-- Left: Checkout Form --}}
        <div class="flex-1 max-w-[800px]">

            {{-- Step Indicator --}}
            <nav class="flex items-center gap-8 mb-12">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">1</span>
                    <span class="font-semibold text-primary">Information</span>
                </div>
                <div class="h-px w-12 bg-outline-variant/30"></div>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-bold">2</span>
                    <span class="font-medium text-on-surface-variant">Shipping</span>
                </div>
                <div class="h-px w-12 bg-outline-variant/30"></div>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-bold">3</span>
                    <span class="font-medium text-on-surface-variant">Payment</span>
                </div>
            </nav>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-10">
                @csrf

                {{-- Contact Section --}}
                <section class="space-y-6">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Contact Information</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Email Address</label>
                            <input name="email" type="email" placeholder="your@email.com" value="{{ old('email', auth()->user()->email ?? '') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Phone Number</label>
                            <input name="phone" type="tel" placeholder="03XX-XXXXXXX" value="{{ old('phone') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                    </div>
                </section>

                {{-- Shipping Address --}}
                <section class="space-y-6 pt-6">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Delivery Address</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">First Name</label>
                            <input name="first_name" type="text" value="{{ old('first_name') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Last Name</label>
                            <input name="last_name" type="text" value="{{ old('last_name') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Street Address</label>
                            <input name="address" type="text" placeholder="House no. and street name" value="{{ old('address') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Apartment / Area (optional)</label>
                            <input name="address2" type="text" value="{{ old('address2') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">City</label>
                            <input name="city" type="text" placeholder="e.g. Karachi, Lahore, Islamabad" value="{{ old('city') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Postal Code</label>
                            <input name="postal_code" type="text" value="{{ old('postal_code') }}"
                                class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>
                    </div>
                </section>

                {{-- Payment Section --}}
                <section class="space-y-6 pt-6">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Payment Method</h2>

                    <input type="hidden" name="payment_method" id="payment_method" value="{{ old('payment_method', 'cod') }}" />

                    {{-- Method Selector Cards --}}
                    <div class="grid grid-cols-3 gap-4">

                        {{-- COD --}}
                        <div class="payment-card rounded-2xl bg-white shadow-sm p-5 text-center {{ old('payment_method', 'cod') === 'cod' ? 'selected' : '' }}"
                             data-method="cod" onclick="selectPayment('cod')">
                            <div class="w-12 h-12 mx-auto mb-3 bg-green-50 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-600 text-2xl" style="font-variation-settings:'FILL' 1;">local_shipping</span>
                            </div>
                            <p class="font-bold text-sm text-slate-800">Cash on Delivery</p>
                            <p class="text-xs text-slate-400 mt-1">Pay when bird arrives</p>
                        </div>

                        {{-- JazzCash --}}
                        <div class="payment-card rounded-2xl bg-white shadow-sm p-5 text-center {{ old('payment_method') === 'jazzcash' ? 'selected' : '' }}"
                             data-method="jazzcash" onclick="selectPayment('jazzcash')">
                            <div class="w-12 h-12 mx-auto mb-3 bg-red-50 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-red-500 text-2xl" style="font-variation-settings:'FILL' 1;">account_balance_wallet</span>
                            </div>
                            <p class="font-bold text-sm text-slate-800">JazzCash</p>
                            <p class="text-xs text-slate-400 mt-1">Mobile wallet transfer</p>
                        </div>

                        {{-- Bank Transfer --}}
                        <div class="payment-card rounded-2xl bg-white shadow-sm p-5 text-center {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}"
                             data-method="bank_transfer" onclick="selectPayment('bank_transfer')">
                            <div class="w-12 h-12 mx-auto mb-3 bg-blue-50 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-500 text-2xl" style="font-variation-settings:'FILL' 1;">account_balance</span>
                            </div>
                            <p class="font-bold text-sm text-slate-800">Bank Transfer</p>
                            <p class="text-xs text-slate-400 mt-1">Direct bank deposit</p>
                        </div>
                    </div>

                    {{-- COD Details --}}
                    <div id="details-cod" class="payment-details {{ old('payment_method', 'cod') === 'cod' ? 'active' : '' }}">
                        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 flex gap-4 items-start">
                            <span class="material-symbols-outlined text-green-600 text-3xl mt-1" style="font-variation-settings:'FILL' 1;">verified</span>
                            <div>
                                <p class="font-bold text-green-800 mb-1">No payment needed right now</p>
                                <p class="text-sm text-green-700 leading-relaxed">Your bird will be carefully packaged and delivered to your door. Pay the full amount in cash upon delivery. Our delivery partner will contact you before arrival.</p>
                            </div>
                        </div>
                    </div>

                    {{-- JazzCash Details --}}
                    <div id="details-jazzcash" class="payment-details {{ old('payment_method') === 'jazzcash' ? 'active' : '' }}">
                        <div class="bg-red-50 border border-red-100 rounded-2xl p-6 space-y-4">

                            @if(session('jazzcash_error'))
                                <div class="flex items-start gap-3 bg-red-100 border border-red-300 rounded-xl p-4 text-sm text-red-700">
                                    <span class="material-symbols-outlined text-red-500 text-xl mt-0.5">error</span>
                                    <p>{{ session('jazzcash_error') }}</p>
                                </div>
                            @endif

                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings:'FILL' 1;">account_balance_wallet</span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Pay with JazzCash Wallet</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Enter your JazzCash mobile number. You will receive a payment confirmation PIN on your JazzCash app — approve it to complete payment.</p>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-4 flex justify-between items-center text-sm">
                                <span class="text-slate-500">Amount to be charged</span>
                                <span class="font-black text-primary text-base">${{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Your JazzCash Mobile Number</label>
                                <input name="jazzcash_number" type="tel" placeholder="e.g. 0300-1234567" value="{{ old('jazzcash_number') }}"
                                    class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all tracking-wider" />
                                <p class="text-[11px] text-slate-400 mt-2 ml-4">A PIN will be sent to this number via the JazzCash app to confirm payment.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Transfer Details --}}
                    <div id="details-bank_transfer" class="payment-details {{ old('payment_method') === 'bank_transfer' ? 'active' : '' }}">
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 space-y-4">
                            <p class="font-bold text-slate-800 text-sm">Transfer to our bank account:</p>
                            <div class="bg-white rounded-xl p-4 space-y-3 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Bank</span>
                                    <span class="font-bold text-slate-800">HBL — Habib Bank Ltd</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Account Title</span>
                                    <span class="font-bold text-slate-800">Bird Haven</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Account Number</span>
                                    <span class="font-bold text-slate-800 tracking-wider font-mono">0000-0000000-000</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">IBAN</span>
                                    <span class="font-bold text-slate-800 font-mono text-xs">PK00HABB0000000000000</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-dashed border-outline-variant/30 pt-3">
                                    <span class="text-slate-500">Amount</span>
                                    <span class="font-black text-primary text-base">${{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500">After transfer, enter your transaction reference / receipt number below:</p>
                            <div>
                                <label class="block text-xs font-bold text-sky-800 uppercase tracking-widest mb-2 ml-4">Transaction Reference</label>
                                <input name="transaction_id" type="text" placeholder="e.g. REF-20260409-001" value="{{ old('transaction_id') }}"
                                    class="w-full px-6 py-4 rounded-full border-none bg-white shadow-sm ring-1 ring-outline-variant/15 focus:ring-2 focus:ring-primary/20 outline-none transition-all font-mono" />
                            </div>
                        </div>
                    </div>

                </section>

                {{-- Bottom Navigation --}}
                <div class="flex items-center justify-between pt-10">
                    <a href="{{ route('cart') }}" class="flex items-center gap-2 text-primary font-semibold hover:opacity-70 transition-opacity">
                        <span class="material-symbols-outlined">chevron_left</span>
                        Return to Cart
                    </a>
                    <button type="submit" class="px-10 py-5 bg-gradient-to-br from-primary to-primary-container text-white font-bold rounded-full shadow-lg hover:-translate-y-0.5 transition-all">
                        Place Order
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: Order Summary Sidebar --}}
        <aside class="w-full md:w-[420px]">
            <div class="bg-surface-container-low rounded-xl p-8 sticky top-32 space-y-8">
                <h3 class="text-xl font-bold text-slate-900 border-b border-outline-variant/20 pb-4">Order Summary</h3>

                {{-- Products --}}
                <div class="space-y-6">
                    @foreach ($cartItems as $item)
                        <div class="flex items-center gap-4">
                            <div class="relative w-20 h-20 rounded-lg overflow-hidden bg-white shadow-sm flex-shrink-0">
                                <img class="w-full h-full object-fill"
                                    alt="{{ $item->bird->name }}"
                                    src="{{ $item->bird->image_url }}" />
                                <span class="absolute -top-2 -right-2 bg-slate-800 text-white text-[10px] w-6 h-6 flex items-center justify-center rounded-full font-bold">{{ $item->quantity }}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ $item->bird->name }}</h4>
                                <p class="text-xs text-on-surface-variant">{{ $item->bird->subtitle }}</p>
                            </div>
                            <span class="font-bold text-slate-900">${{ number_format($item->bird->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="space-y-3 pt-4 border-t border-outline-variant/20">
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span class="font-semibold text-slate-900">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Shipping</span>
                        <span class="text-secondary font-medium italic">Calculated at next step</span>
                    </div>
                    <div class="flex justify-between text-xl font-black text-slate-900 pt-4">
                        <span>Total</span>
                        <div class="text-right">
                            <span class="text-xs font-medium text-on-surface-variant mr-2">USD</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Trust Badges --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-green-600 text-base" style="font-variation-settings:'FILL' 1;">verified_user</span>
                        Secure order — your info is protected
                    </div>
                    <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-base" style="font-variation-settings:'FILL' 1;">local_shipping</span>
                        Nationwide delivery across Pakistan
                    </div>
                    <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-secondary text-base" style="font-variation-settings:'FILL' 1;">eco</span>
                        5% of proceeds support habitat restoration
                    </div>
                </div>
            </div>
        </aside>
    </main>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <main class="md:hidden pt-20 pb-44">

        {{-- Step Indicator --}}
        <section class="px-6 py-8">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-[2px] bg-surface-container-highest -z-10 -translate-y-1/2"></div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center ring-4 ring-primary-container/30">
                        <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings:'FILL' 1;">check</span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-primary">Info</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-primary-container flex items-center justify-center">
                        <span class="text-white font-bold text-sm">2</span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface">Payment</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center">
                        <span class="text-outline font-bold text-sm">3</span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-outline">Confirm</span>
                </div>
            </div>
        </section>

        {{-- Order Summary Card --}}
        <section class="px-6 mb-8">
            <div class="bg-surface-container-lowest p-6 rounded-lg shadow-[0_20px_40px_rgba(25,28,29,0.06)] border border-outline-variant/10">
                <h2 class="font-headline font-bold text-lg mb-4">Your Selection</h2>
                @foreach ($cartItems as $item)
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-lg bg-surface-container-low overflow-hidden flex-shrink-0">
                            <img class="w-full h-full object-cover" alt="{{ $item->bird->name }}" src="{{ $item->bird->image_url }}" />
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm">{{ $item->bird->name }}</p>
                            <p class="text-xs text-outline">{{ $item->bird->subtitle }}</p>
                        </div>
                        <p class="font-headline font-bold text-primary">${{ number_format($item->bird->price * $item->quantity, 2) }}</p>
                    </div>
                @endforeach
                <div class="pt-4 border-t border-dashed border-outline-variant/30 flex justify-between items-center">
                    <span class="text-sm font-medium text-outline">Total</span>
                    <span class="font-bold text-on-surface">${{ number_format($subtotal, 2) }}</span>
                </div>
            </div>
        </section>

        {{-- Mobile Form --}}
        <form action="{{ route('checkout.process') }}" method="POST" class="px-6 space-y-6">
            @csrf

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            {{-- Contact --}}
            <div class="space-y-4">
                <h3 class="font-headline font-bold text-on-surface">Contact</h3>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">Email</label>
                    <input name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                        class="w-full px-6 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">Phone</label>
                    <input name="phone" type="tel" placeholder="03XX-XXXXXXX" value="{{ old('phone') }}"
                        class="w-full px-6 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                </div>
            </div>

            {{-- Address --}}
            <div class="space-y-4">
                <h3 class="font-headline font-bold text-on-surface">Delivery Address</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">First Name</label>
                        <input name="first_name" type="text" value="{{ old('first_name') }}"
                            class="w-full px-4 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">Last Name</label>
                        <input name="last_name" type="text" value="{{ old('last_name') }}"
                            class="w-full px-4 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">Address</label>
                    <input name="address" type="text" value="{{ old('address') }}"
                        class="w-full px-6 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">City</label>
                        <input name="city" type="text" value="{{ old('city') }}"
                            class="w-full px-4 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-outline ml-4">Postal Code</label>
                        <input name="postal_code" type="text" value="{{ old('postal_code') }}"
                            class="w-full px-4 py-4 rounded-lg bg-surface-container-lowest ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/40 outline-none text-sm" />
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="space-y-4">
                <h3 class="font-headline font-bold text-on-surface">Payment Method</h3>

                <input type="hidden" name="payment_method" id="payment_method_mobile" value="{{ old('payment_method', 'cod') }}" />

                <div class="grid grid-cols-3 gap-3">
                    <div class="payment-card rounded-xl bg-white shadow-sm p-4 text-center {{ old('payment_method', 'cod') === 'cod' ? 'selected' : '' }}"
                         data-method="cod" onclick="selectPaymentMobile('cod')">
                        <span class="material-symbols-outlined text-green-600 text-2xl mb-1" style="font-variation-settings:'FILL' 1;">local_shipping</span>
                        <p class="font-bold text-xs text-slate-700">COD</p>
                    </div>
                    <div class="payment-card rounded-xl bg-white shadow-sm p-4 text-center {{ old('payment_method') === 'jazzcash' ? 'selected' : '' }}"
                         data-method="jazzcash" onclick="selectPaymentMobile('jazzcash')">
                        <span class="material-symbols-outlined text-red-500 text-2xl mb-1" style="font-variation-settings:'FILL' 1;">account_balance_wallet</span>
                        <p class="font-bold text-xs text-slate-700">JazzCash</p>
                    </div>
                    <div class="payment-card rounded-xl bg-white shadow-sm p-4 text-center {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}"
                         data-method="bank_transfer" onclick="selectPaymentMobile('bank_transfer')">
                        <span class="material-symbols-outlined text-blue-500 text-2xl mb-1" style="font-variation-settings:'FILL' 1;">account_balance</span>
                        <p class="font-bold text-xs text-slate-700">Bank</p>
                    </div>
                </div>

                {{-- COD info --}}
                <div id="mobile-details-cod" class="payment-details {{ old('payment_method', 'cod') === 'cod' ? 'active' : '' }}">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700">
                        <p class="font-bold mb-1">Pay when delivered</p>
                        <p class="text-xs">No payment needed now. Our delivery partner will contact you before arrival.</p>
                    </div>
                </div>

                {{-- JazzCash info --}}
                <div id="mobile-details-jazzcash" class="payment-details {{ old('payment_method') === 'jazzcash' ? 'active' : '' }}">
                    <div class="bg-red-50 border border-red-100 rounded-xl p-4 space-y-3">
                        @if(session('jazzcash_error'))
                            <p class="text-xs text-red-600 font-semibold bg-red-100 rounded-lg p-2">{{ session('jazzcash_error') }}</p>
                        @endif
                        <p class="text-xs text-slate-600">Enter your JazzCash number. You'll get a PIN on your app to confirm — amount will be auto-deducted.</p>
                        <div class="bg-white rounded-lg p-3 flex justify-between text-xs">
                            <span class="text-slate-500">Amount</span>
                            <span class="font-black text-primary">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <input name="jazzcash_number" type="tel" placeholder="03XX-XXXXXXX" value="{{ old('jazzcash_number') }}"
                            class="w-full px-4 py-3 rounded-lg bg-white ring-1 ring-outline-variant/20 outline-none text-sm tracking-wider" />
                    </div>
                </div>

                {{-- Bank Transfer info --}}
                <div id="mobile-details-bank_transfer" class="payment-details {{ old('payment_method') === 'bank_transfer' ? 'active' : '' }}">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 space-y-3">
                        <div class="bg-white rounded-lg p-3 text-xs space-y-2">
                            <div class="flex justify-between"><span class="text-slate-500">Bank</span><span class="font-bold">HBL</span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Title</span><span class="font-bold">Bird Haven</span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Account</span><span class="font-bold font-mono">0000-0000000-000</span></div>
                            <div class="flex justify-between border-t border-dashed pt-2"><span class="text-slate-500">Amount</span><span class="font-black text-primary">${{ number_format($subtotal, 2) }}</span></div>
                        </div>
                        <input name="transaction_id" type="text" placeholder="Transaction Reference / Receipt No." value="{{ old('transaction_id') }}"
                            class="w-full px-4 py-3 rounded-lg bg-white ring-1 ring-outline-variant/20 outline-none text-sm font-mono" />
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold text-lg rounded-full shadow-[0_10px_20px_rgba(0,101,139,0.3)]">
                Place Order — ${{ number_format($subtotal, 2) }}
            </button>
        </form>
    </main>

@endsection

@push('scripts')
<script>
function selectPayment(method) {
    // Update hidden input
    document.getElementById('payment_method').value = method;

    // Update card highlight
    document.querySelectorAll('.payment-card[data-method]').forEach(card => {
        card.classList.toggle('selected', card.dataset.method === method);
    });

    // Show correct detail panel (desktop)
    document.querySelectorAll('#details-cod, #details-jazzcash, #details-bank_transfer').forEach(el => {
        el.classList.remove('active');
    });
    const target = document.getElementById('details-' + method);
    if (target) target.classList.add('active');
}

function selectPaymentMobile(method) {
    document.getElementById('payment_method_mobile').value = method;

    document.querySelectorAll('.payment-card[data-method]').forEach(card => {
        card.classList.toggle('selected', card.dataset.method === method);
    });

    document.querySelectorAll('#mobile-details-cod, #mobile-details-jazzcash, #mobile-details-bank_transfer').forEach(el => {
        el.classList.remove('active');
    });
    const target = document.getElementById('mobile-details-' + method);
    if (target) target.classList.add('active');
}
</script>
@endpush
