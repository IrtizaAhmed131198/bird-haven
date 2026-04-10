@extends('layouts.admin')

@section('title', $user->name . ' | BirdHaven Admin')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">User Profile</h2>
            <p class="text-on-surface-variant mt-1">Viewing details for {{ $user->name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-8 flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-full bg-primary-container flex items-center justify-center text-primary font-black text-3xl font-headline mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h3 class="font-headline font-bold text-xl text-on-surface">{{ $user->name }}</h3>
            <p class="text-sm text-on-surface-variant mt-1">{{ $user->email }}</p>
            <span class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                {{ $user->is_admin ? 'bg-inverse-surface text-white' : 'bg-surface-container-high text-on-surface-variant' }}">
                {{ $user->is_admin ? 'Admin' : 'Customer' }}
            </span>
            <p class="text-xs text-on-surface-variant mt-4">Joined {{ $user->created_at->format('d M Y') }}</p>
            <div class="flex gap-3 mt-6 w-full">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="flex-1 py-2.5 bg-primary text-white font-bold rounded-DEFAULT text-sm text-center hover:scale-[1.02] transition-all">
                    Edit
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Delete this user?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-full py-2.5 bg-error-container/30 text-error font-bold rounded-DEFAULT text-sm hover:bg-error-container/50 transition-all">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Orders --}}
        <div class="md:col-span-2 bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-container">
                <h3 class="font-headline font-bold text-lg">Order History</h3>
            </div>
            <div class="divide-y divide-outline-variant/10">
                @forelse($user->orders as $order)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-sm text-on-surface">#BH-{{ $order->id }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                            {{ $order->status === 'paid' ? 'bg-tertiary-container/20 text-on-tertiary-container' : 'bg-secondary-container/30 text-on-secondary-container' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="font-bold text-sm">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-on-surface-variant text-sm">No orders found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
