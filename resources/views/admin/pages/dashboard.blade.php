@extends('layouts.admin')

@section('title', 'Dashboard | BirdHaven Admin')

@section('content')

{{-- Welcome Header --}}
<section class="flex justify-between items-end mb-8">
    <div>
        <h2 class="text-3xl font-extrabold font-headline text-on-surface tracking-tight">Guardian's Console</h2>
        <p class="text-on-surface-variant mt-1">Real-time overview of the BirdHaven ecosystem.</p>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-surface-container-high text-on-surface rounded-DEFAULT font-medium text-sm flex items-center gap-2 hover:bg-surface-container-highest transition-all">
            <span class="material-symbols-outlined text-sm">calendar_today</span>
            This Week
        </button>
    </div>
</section>

{{-- KPI Grid --}}
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-card p-6 rounded-lg relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all"></div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-primary/10 text-primary rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">flutter_dash</span>
            </div>
            <span class="text-sm font-medium text-on-surface-variant">Total Birds</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold font-headline">{{ $totalBirds }}</span>
            <span class="text-xs font-bold text-tertiary">Listed</span>
        </div>
    </div>

    <div class="glass-card p-6 rounded-lg relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/5 rounded-full blur-2xl group-hover:bg-secondary/10 transition-all"></div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-secondary/10 text-secondary rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">shopping_bag</span>
            </div>
            <span class="text-sm font-medium text-on-surface-variant">Total Orders</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold font-headline">{{ $totalOrders }}</span>
            <span class="text-xs font-bold text-secondary">Orders</span>
        </div>
    </div>

    <div class="glass-card p-6 rounded-lg relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-tertiary/5 rounded-full blur-2xl group-hover:bg-tertiary/10 transition-all"></div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-tertiary/10 text-tertiary rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
            </div>
            <span class="text-sm font-medium text-on-surface-variant">Total Revenue</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold font-headline">${{ number_format($totalRevenue, 0) }}</span>
        </div>
    </div>

    <div class="glass-card p-6 rounded-lg relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-container/10 rounded-full blur-2xl group-hover:bg-primary-container/20 transition-all"></div>
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-primary-container/20 text-on-primary-container rounded-xl">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">person_add</span>
            </div>
            <span class="text-sm font-medium text-on-surface-variant">Total Users</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-extrabold font-headline">{{ $totalUsers }}</span>
            <span class="text-xs font-medium text-on-surface-variant">registered</span>
        </div>
    </div>
</section>

{{-- Middle Row --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    {{-- Recent Orders Table --}}
    <div class="lg:col-span-2 bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
        <div class="px-8 py-6 flex justify-between items-center border-b border-surface-container">
            <h3 class="text-lg font-bold font-headline">Recent Transactions</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary font-semibold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest bg-surface-container-low">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-surface-container">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-6 py-4 font-medium">#BH-{{ $order->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-[10px] font-bold">
                                    {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                </div>
                                {{ $order->user->name ?? 'Guest' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                {{ $order->status === 'paid' ? 'bg-tertiary-container/20 text-on-tertiary-container' : 'bg-secondary-container/30 text-on-secondary-container' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold">${{ number_format($order->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant text-sm">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-8 rounded-lg text-white shadow-xl flex flex-col">
        <h3 class="text-lg font-bold font-headline mb-6">Quick Actions</h3>
        <div class="space-y-4 flex-1">
            <a href="{{ route('admin.birds.create') }}" class="w-full flex items-center gap-4 p-4 rounded-lg bg-white/5 hover:bg-white/10 transition-all group">
                <span class="material-symbols-outlined text-sky-400 group-hover:scale-110 transition-transform">add_circle</span>
                <span class="text-sm font-semibold">Add New Bird</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="w-full flex items-center gap-4 p-4 rounded-lg bg-white/5 hover:bg-white/10 transition-all group">
                <span class="material-symbols-outlined text-sky-400 group-hover:scale-110 transition-transform">group</span>
                <span class="text-sm font-semibold">Manage Users</span>
            </a>
            <a href="{{ route('admin.cms.pages.index') }}" class="w-full flex items-center gap-4 p-4 rounded-lg bg-white/5 hover:bg-white/10 transition-all group">
                <span class="material-symbols-outlined text-sky-400 group-hover:scale-110 transition-transform">description</span>
                <span class="text-sm font-semibold">CMS Pages</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="w-full flex items-center gap-4 p-4 rounded-lg bg-white/5 hover:bg-white/10 transition-all group">
                <span class="material-symbols-outlined text-sky-400 group-hover:scale-110 transition-transform">settings</span>
                <span class="text-sm font-semibold">Settings</span>
            </a>
        </div>
        <div class="mt-8 p-4 bg-sky-500/10 rounded-lg border border-sky-500/20">
            <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2">System Status</p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                <span class="text-xs font-medium">All systems operational</span>
            </div>
        </div>
    </div>
</section>

@endsection
