@extends('layouts.app')

@section('title', 'Order History | Bird Haven')

@section('content')

    {{-- ===== DESKTOP LAYOUT ===== --}}
    <div class="hidden md:flex max-w-[1440px] mx-auto pt-24 min-h-screen">

        <x-account-sidebar />

        {{-- Desktop Main Content --}}
        <main class="flex-1 ml-64 px-12 pb-24">
            <header class="mb-12 mt-8">
                <h1 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface mb-2">Order History</h1>
                <p class="text-on-surface-variant">Review your ethical adoptions and guardian supply history.</p>
            </header>

            {{-- Stats Cards --}}
            <section class="mb-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_20px_40px_rgba(25,28,29,0.06)] border border-white/20">
                    <div class="flex justify-between items-start mb-4">
                        <span class="material-symbols-outlined text-sky-700 bg-sky-50 p-3 rounded-full">shopping_bag</span>
                        <span class="text-xs font-bold text-sky-700 uppercase tracking-wider">Total Spent</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-on-surface">${{ number_format($stats['total_spent'], 2) }}</p>
                    <p class="text-sm text-slate-500 mt-1">Across {{ $stats['order_count'] }} Sanctuary Orders</p>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_20px_40px_rgba(25,28,29,0.06)] border border-white/20">
                    <div class="flex justify-between items-start mb-4">
                        <span class="material-symbols-outlined text-emerald-700 bg-emerald-50 p-3 rounded-full">flutter_dash</span>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Adoptions</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-on-surface">{{ $stats['bird_count'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Birds currently in your care</p>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_20px_40px_rgba(25,28,29,0.06)] border border-white/20">
                    <div class="flex justify-between items-start mb-4">
                        <span class="material-symbols-outlined text-amber-700 bg-amber-50 p-3 rounded-full">verified</span>
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Status</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-on-surface">{{ $stats['membership'] ?? 'Premium' }}</p>
                    <p class="text-sm text-slate-500 mt-1">Guardian Level Member</p>
                </div>
            </section>

            {{-- Orders Table --}}
            <div class="bg-surface-container-lowest rounded-lg shadow-[0_20px_40px_rgba(25,28,29,0.06)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low">
                                <th class="px-8 py-5 text-xs font-bold text-on-surface-variant uppercase tracking-widest font-headline">Order ID</th>
                                <th class="px-8 py-5 text-xs font-bold text-on-surface-variant uppercase tracking-widest font-headline">Date</th>
                                <th class="px-8 py-5 text-xs font-bold text-on-surface-variant uppercase tracking-widest font-headline">Status</th>
                                <th class="px-8 py-5 text-xs font-bold text-on-surface-variant uppercase tracking-widest font-headline text-right">Total Price</th>
                                <th class="px-8 py-5 text-xs font-bold text-on-surface-variant uppercase tracking-widest font-headline text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-surface-bright transition-all duration-300">
                                    <td class="px-8 py-6 font-bold text-on-surface">#{{ $order->order_number }}</td>
                                    <td class="px-8 py-6 text-slate-600">{{ $order->created_at->format('F d, Y') }}</td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusClasses = [
                                                'delivered' => 'bg-emerald-100 text-emerald-800',
                                                'arrived'   => 'bg-emerald-100 text-emerald-800',
                                                'transit'   => 'bg-sky-100 text-sky-800',
                                                'preparing' => 'bg-amber-100 text-amber-800',
                                                'cancelled' => 'bg-surface-container-highest text-on-surface-variant',
                                            ];
                                            $cls = $statusClasses[strtolower($order->status)] ?? 'bg-surface-container text-on-surface-variant';
                                        @endphp
                                        <span class="px-4 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right font-semibold text-on-surface">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('order.show', $order->id) }}" class="text-sky-700 font-bold text-sm hover:underline hover:translate-x-1 inline-flex items-center space-x-1 transition-all duration-300">
                                            <span>View Details</span><span class="material-symbols-outlined text-sm">chevron_right</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-16 text-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-5xl mb-3 block text-outline-variant">receipt_long</span>
                                        No orders found. <a href="{{ route('shop') }}" class="text-primary font-bold hover:underline">Start shopping</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Guardian Resource Banner --}}
            <div class="mt-12 p-10 bg-sky-900 rounded-lg relative overflow-hidden text-white flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="relative z-10 max-w-lg">
                    <h3 class="text-2xl font-headline font-bold mb-3">Guardian Resource Portal</h3>
                    <p class="text-sky-100 opacity-90 leading-relaxed">Access exclusive veterinary guides and sourcing documentation for your adopted sanctuary residents.</p>
                </div>
                <button class="relative z-10 bg-white text-sky-900 px-8 py-4 rounded-full font-bold hover:scale-105 transition-transform duration-300 shadow-xl">
                    Download Guardian Pack
                </button>
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-sky-400/20 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -top-20 w-80 h-80 bg-sky-300/10 rounded-full blur-3xl"></div>
            </div>
        </main>
    </div>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <main class="md:hidden pt-24 pb-32 px-6 max-w-md mx-auto">

        @forelse ($orders as $order)
            @php
                $mobileStatusClasses = [
                    'delivered' => 'bg-secondary-container text-on-secondary-container',
                    'arrived'   => 'bg-secondary-container text-on-secondary-container',
                    'transit'   => 'bg-tertiary-container text-on-tertiary-container',
                    'preparing' => 'bg-primary-container text-on-primary-container',
                    'cancelled' => 'bg-surface-container-highest text-on-surface-variant',
                ];
                $mCls = $mobileStatusClasses[strtolower($order->status)] ?? 'bg-surface-container text-on-surface-variant';
            @endphp
            <div class="mb-5 p-6 bg-surface-container-lowest rounded-lg shadow-[0_20px_40px_rgba(25,28,29,0.06)] hover:-translate-y-0.5 transition-transform duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="font-headline text-[10px] font-bold text-primary tracking-widest uppercase mb-1 block">#{{ $order->order_number }}</span>
                        <h3 class="font-headline font-bold text-lg text-on-surface">
                            {{ $order->items->first()?->bird?->name ?? 'Order Items' }}
                        </h3>
                        <p class="text-sm text-on-surface-variant">{{ $order->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="px-3 py-1 rounded-full text-[11px] font-bold {{ $mCls }}">{{ ucfirst($order->status) }}</div>
                </div>
                <div class="flex items-center justify-between mt-6">
                    <div>
                        <span class="text-xs opacity-60">Total Amount</span>
                        <p class="font-headline font-extrabold text-xl">${{ number_format($order->total, 2) }}</p>
                    </div>
                    <a href="{{ route('order.show', $order->id) }}" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-br from-primary to-primary-container text-white rounded-full font-headline font-bold text-sm shadow-lg shadow-primary/20 active:scale-95 transition-all">
                        Details <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <span class="material-symbols-outlined text-6xl text-outline-variant mb-4 block">receipt_long</span>
                <h3 class="text-xl font-bold mb-2">No orders yet</h3>
                <a href="{{ route('shop') }}" class="text-primary font-bold hover:underline">Explore our species</a>
            </div>
        @endforelse
    </main>

@endsection
