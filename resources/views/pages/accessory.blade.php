@extends('layouts.app')

@section('title', $accessory->name . ' | Bird Haven')

@section('content')

{{-- ===== DESKTOP ===== --}}
<main class="hidden md:block pt-32 pb-24 px-12 max-w-[1440px] mx-auto">

    <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-8 tracking-widest uppercase">
        <a href="{{ route('accessories') }}" class="hover:text-primary transition-colors">Accessories</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-on-surface-variant">{{ $accessory->type_label }}</span>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-primary font-semibold">{{ $accessory->name }}</span>
    </nav>

    <div class="grid grid-cols-12 gap-12 items-start mb-20">

        {{-- Image --}}
        <div class="col-span-6">
            <div class="aspect-square rounded-2xl overflow-hidden bg-surface-container">
                <img src="{{ $accessory->image_url }}" alt="{{ $accessory->name }}"
                    class="w-full h-full object-cover"
                    onerror="this.src='{{ asset('assets/images/default.png') }}'" />
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="col-span-6 sticky top-32 space-y-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-secondary">{{ $accessory->type_label }}</span>
                <h1 class="text-4xl font-headline font-extrabold tracking-tight text-on-surface mt-2 mb-3">{{ $accessory->name }}</h1>
                <p class="text-on-surface-variant leading-relaxed">{{ $accessory->description }}</p>
            </div>

            <div class="flex items-baseline gap-3">
                <span class="text-4xl font-headline font-black text-on-surface">${{ number_format($accessory->price, 2) }}</span>
                @if ($accessory->original_price && $accessory->original_price > $accessory->price)
                    <span class="text-lg text-on-surface-variant line-through">${{ number_format($accessory->original_price, 2) }}</span>
                    <span class="px-2 py-0.5 bg-secondary-container text-on-secondary-container text-xs font-bold rounded-full">
                        SAVE {{ round((1 - $accessory->price / $accessory->original_price) * 100) }}%
                    </span>
                @endif
            </div>

            {{-- Stock Status --}}
            @php $stock = $accessory->stock_status; @endphp
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full {{ $stock['level'] === 'out' ? 'bg-red-500' : ($stock['level'] === 'low' ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                <span class="text-sm font-semibold {{ $stock['level'] === 'out' ? 'text-red-600' : ($stock['level'] === 'low' ? 'text-amber-600' : 'text-emerald-600') }}">
                    {{ $stock['label'] }}
                </span>
            </div>

            @if ($accessory->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="hidden" name="accessory_id" value="{{ $accessory->id }}" />
                    <button type="submit"
                        class="flex-1 py-5 rounded-full bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-lg shadow-[0_20px_40px_rgba(0,101,139,0.2)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">shopping_bag</span> Add to Cart
                    </button>
                </form>
            @else
                <div class="py-5 rounded-full bg-surface-container text-on-surface-variant font-bold text-center">
                    Currently Out of Stock
                </div>
            @endif

            <p class="text-xs text-on-surface-variant flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-secondary">verified</span>
                Ethically sourced. Ships with bird orders for free.
            </p>
        </div>
    </div>

    {{-- Related Accessories --}}
    @if ($related->count())
        <section>
            <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">More from {{ $accessory->type_label }}</h2>
            <div class="grid grid-cols-4 gap-5">
                @foreach ($related as $item)
                    <div class="group bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
                        <a href="{{ route('accessory.show', $item->slug) }}" class="block aspect-square overflow-hidden bg-surface-container">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                        </a>
                        <div class="p-4">
                            <h3 class="font-bold text-sm text-on-surface mb-1 truncate">{{ $item->name }}</h3>
                            <div class="flex items-center justify-between mt-2">
                                <span class="font-bold text-primary">${{ number_format($item->price, 2) }}</span>
                                @if ($item->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="accessory_id" value="{{ $item->id }}" />
                                        <button type="submit" class="p-2 bg-primary text-white rounded-full hover:scale-110 transition-all">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</main>

{{-- ===== MOBILE ===== --}}
<div class="md:hidden">
    <div class="aspect-square bg-surface-container overflow-hidden">
        <img src="{{ $accessory->image_url }}" alt="{{ $accessory->name }}"
            class="w-full h-full object-cover"
            onerror="this.src='{{ asset('assets/images/default.png') }}'" />
    </div>

    <div class="px-6 pt-6 pb-32">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-1 block">{{ $accessory->type_label }}</span>
        <h1 class="text-2xl font-extrabold font-headline text-on-surface mb-2">{{ $accessory->name }}</h1>
        <p class="text-on-surface-variant text-sm leading-relaxed mb-4">{{ $accessory->description }}</p>

        <div class="flex items-baseline gap-2 mb-4">
            <span class="text-2xl font-black font-headline text-primary">${{ number_format($accessory->price, 2) }}</span>
            @if ($accessory->original_price && $accessory->original_price > $accessory->price)
                <span class="text-sm text-on-surface-variant line-through">${{ number_format($accessory->original_price, 2) }}</span>
            @endif
        </div>

        @php $stock = $accessory->stock_status; @endphp
        <div class="flex items-center gap-2 mb-6">
            <span class="w-2 h-2 rounded-full {{ $stock['level'] === 'out' ? 'bg-red-500' : ($stock['level'] === 'low' ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
            <span class="text-sm {{ $stock['level'] === 'out' ? 'text-red-600' : 'text-emerald-600' }}">{{ $stock['label'] }}</span>
        </div>

        @if ($related->count())
            <h3 class="font-bold text-base mb-3">More Like This</h3>
            <div class="flex gap-3 overflow-x-auto pb-2" style="-ms-overflow-style:none;scrollbar-width:none;">
                @foreach ($related as $item)
                    <a href="{{ route('accessory.show', $item->slug) }}" class="flex-none w-28">
                        <div class="aspect-square rounded-lg overflow-hidden mb-1 bg-surface-container">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover" />
                        </div>
                        <p class="text-xs font-semibold truncate">{{ $item->name }}</p>
                        <p class="text-xs text-primary font-bold">${{ number_format($item->price, 2) }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Mobile Sticky CTA --}}
    <div class="fixed bottom-0 left-0 w-full z-50 bg-white/95 backdrop-blur-2xl rounded-t-[3rem] shadow-[0_-10px_30px_rgba(0,0,0,0.08)] px-6 pb-8 pt-6">
        <div class="flex justify-between items-center mb-4 px-2">
            <p class="text-2xl font-black font-headline text-on-surface">${{ number_format($accessory->price, 2) }}</p>
        </div>
        @if ($accessory->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="accessory_id" value="{{ $accessory->id }}" />
                <button type="submit" class="block w-full py-5 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-lg rounded-full shadow-[0_10px_20px_rgba(0,101,139,0.3)] text-center">
                    Add to Cart
                </button>
            </form>
        @else
            <div class="block w-full py-5 bg-surface-container text-on-surface-variant font-bold text-center rounded-full">Out of Stock</div>
        @endif
    </div>
</div>

@endsection
