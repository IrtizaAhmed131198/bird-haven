@extends('layouts.admin')

@section('title', $accessory->name . ' | BirdHaven Admin')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.accessories.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <nav class="flex text-[10px] font-bold text-on-surface-variant uppercase tracking-widest gap-2 mb-1">
                    <a href="{{ route('admin.accessories.index') }}" class="hover:text-primary transition-colors">Accessories</a>
                    <span>/</span>
                    <span class="text-primary">{{ $accessory->name }}</span>
                </nav>
                <h2 class="font-headline font-extrabold text-3xl text-on-surface">{{ $accessory->name }}</h2>
            </div>
        </div>
        <a href="{{ route('admin.accessories.edit', $accessory) }}"
           class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Image & Quick Stats --}}
        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
                <img src="{{ $accessory->image_url }}"
                     alt="{{ $accessory->name }}"
                     onerror="this.src='{{ asset('assets/images/default.png') }}'"
                     class="w-full h-56 object-cover">
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-headline font-black text-primary">${{ number_format($accessory->price, 2) }}</span>
                        @if($accessory->original_price)
                            <span class="text-sm text-on-surface-variant line-through">${{ number_format($accessory->original_price, 2) }}</span>
                        @endif
                    </div>

                    {{-- Type badge --}}
                    <span class="inline-block px-3 py-1 bg-primary-fixed/30 text-on-primary-container rounded-full text-[10px] font-bold uppercase tracking-wider">
                        {{ $accessory->type_label }}
                    </span>

                    {{-- Stock badge --}}
                    @php
                        $stock = $accessory->stock_status;
                        $dotColors = ['in' => 'bg-tertiary', 'low' => 'bg-secondary', 'out' => 'bg-error'];
                        $bgColors  = ['in' => 'bg-tertiary-container text-on-tertiary-container', 'low' => 'bg-secondary-container text-on-secondary-container', 'out' => 'bg-error-container text-on-error-container'];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold {{ $bgColors[$stock['level']] }}">
                        <span class="w-2 h-2 rounded-full {{ $dotColors[$stock['level']] }}"></span>
                        {{ $stock['label'] }}
                    </span>

                    <div class="flex gap-2 pt-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold
                            {{ $accessory->is_active ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-error-container text-on-error-container' }}">
                            <span class="material-symbols-outlined text-[12px]">{{ $accessory->is_active ? 'visibility' : 'visibility_off' }}</span>
                            {{ $accessory->is_active ? 'Visible' : 'Hidden' }}
                        </span>
                        @if($accessory->is_featured)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-primary-fixed text-on-primary-container">
                            <span class="material-symbols-outlined text-[12px]">star</span>
                            Featured
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                <h3 class="text-sm font-bold text-on-surface mb-4">Product Information</h3>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">

                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Type</dt>
                        <dd class="text-on-surface font-medium">{{ $accessory->type_label }}</dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Slug</dt>
                        <dd class="text-on-surface-variant font-mono text-xs">{{ $accessory->slug }}</dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Stock</dt>
                        <dd class="text-on-surface font-medium">{{ $accessory->stock }} units</dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Added</dt>
                        <dd class="text-on-surface">{{ $accessory->created_at->format('d M, Y') }}</dd>
                    </div>

                    @if($accessory->description)
                    <div class="col-span-2">
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Description</dt>
                        <dd class="text-on-surface leading-relaxed">{{ $accessory->description }}</dd>
                    </div>
                    @endif

                </dl>
            </div>

        </div>
    </div>

    {{-- Delete --}}
    <div class="flex justify-end mt-6">
        <form action="{{ route('admin.accessories.destroy', $accessory) }}" method="POST"
              onsubmit="return confirm('Permanently delete {{ $accessory->name }}?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 bg-error-container text-on-error-container rounded-xl font-bold text-sm hover:bg-error hover:text-white transition-all active:scale-95">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                Delete Accessory
            </button>
        </form>
    </div>

</div>

@endsection
