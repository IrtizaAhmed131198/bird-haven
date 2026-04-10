@extends('layouts.app')

@section('title', 'Accessories | Bird Haven')

@section('content')

{{-- ===== DESKTOP ===== --}}
<main class="hidden md:block pt-32 pb-24 px-12 max-w-[1440px] mx-auto">

    <header class="mb-12">
        <div class="flex items-center gap-2 text-sm text-on-surface-variant mb-4 tracking-widest uppercase">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Sanctuary</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-primary font-bold">Accessories</span>
        </div>
        <div class="flex items-end justify-between gap-6">
            <div>
                <h1 class="text-5xl font-extrabold tracking-tighter text-on-surface mb-3">Guardian Essentials</h1>
                <p class="text-on-surface-variant text-lg max-w-xl">Everything your bird needs to thrive — curated cages, premium nutrition, enrichment toys and more.</p>
            </div>
            <a href="{{ route('shop') }}" class="text-primary font-semibold flex items-center gap-1.5 hover:gap-3 transition-all text-sm">
                Browse Birds <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </header>

    <div class="flex gap-10 items-start">

        {{-- Sidebar Filters --}}
        <aside class="w-56 shrink-0 sticky top-28 space-y-6">
            <form method="GET" action="{{ route('accessories') }}" id="filter-form">

                <div class="space-y-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Category</p>
                    <div class="space-y-1.5">
                        <a href="{{ route('accessories', request()->except('type')) }}"
                            class="block text-sm px-3 py-2 rounded-lg transition-colors {{ !request('type') ? 'bg-primary text-white font-semibold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                            All Categories
                        </a>
                        @foreach ($types as $key => $label)
                            <a href="{{ route('accessories', array_merge(request()->except('type'), ['type' => $key])) }}"
                                class="block text-sm px-3 py-2 rounded-lg transition-colors {{ request('type') === $key ? 'bg-primary text-white font-semibold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-2 mt-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Sort</p>
                    <select name="sort" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all">
                        <option value="featured"   {{ request('sort','featured') === 'featured'   ? 'selected' : '' }}>Featured</option>
                        <option value="price_low"  {{ request('sort') === 'price_low'  ? 'selected' : '' }}>Price: Low → High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High → Low</option>
                        <option value="newest"     {{ request('sort') === 'newest'     ? 'selected' : '' }}>Newest</option>
                    </select>
                </div>

                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                @endif
            </form>
        </aside>

        {{-- Grid --}}
        <div class="flex-1">
            {{-- Search --}}
            <div class="relative mb-6">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <form method="GET" action="{{ route('accessories') }}">
                    @if (request('type'))  <input type="hidden" name="type"  value="{{ request('type') }}" /> @endif
                    @if (request('sort'))  <input type="hidden" name="sort"  value="{{ request('sort') }}" /> @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search accessories…"
                        class="w-full pl-12 pr-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </form>
            </div>

            @if ($accessories->isEmpty())
                <div class="text-center py-24">
                    <span class="material-symbols-outlined text-6xl text-outline-variant mb-4 block">storefront</span>
                    <h3 class="text-xl font-bold mb-2">No accessories found</h3>
                    <a href="{{ route('accessories') }}" class="text-primary hover:underline text-sm">Clear filters</a>
                </div>
            @else
                <div class="grid grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($accessories as $item)
                        <div class="group bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10 hover:shadow-md transition-shadow">
                            <a href="{{ route('accessory.show', $item->slug) }}" class="block aspect-square overflow-hidden bg-surface-container">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                            </a>
                            <div class="p-5">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary mb-1 block">{{ $item->type_label }}</span>
                                <a href="{{ route('accessory.show', $item->slug) }}">
                                    <h3 class="font-bold text-on-surface mb-1 hover:text-primary transition-colors">{{ $item->name }}</h3>
                                </a>
                                <p class="text-xs text-on-surface-variant mb-4 line-clamp-2">{{ $item->description }}</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-xl font-extrabold text-primary">${{ number_format($item->price, 2) }}</span>
                                        @if ($item->original_price && $item->original_price > $item->price)
                                            <span class="text-xs text-on-surface-variant line-through ml-1">${{ number_format($item->original_price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if ($item->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="accessory_id" value="{{ $item->id }}" />
                                            <button type="submit"
                                                class="p-2.5 bg-primary text-white rounded-full hover:scale-110 active:scale-95 transition-all shadow-md shadow-primary/20"
                                                title="Add to cart">
                                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs font-semibold text-red-500 bg-red-50 px-2.5 py-1 rounded-full">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($accessories->hasPages())
                    <div class="mt-10">{{ $accessories->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</main>

{{-- ===== MOBILE ===== --}}
<div class="md:hidden pt-24 pb-24">
    <div class="px-6 mb-6">
        <h1 class="text-3xl font-extrabold font-headline tracking-tight text-on-surface mb-1">Guardian Essentials</h1>
        <p class="text-on-surface-variant text-sm">Premium accessories for your birds.</p>
    </div>

    {{-- Mobile Type Pills --}}
    <div class="flex gap-2 overflow-x-auto px-6 pb-2 mb-4" style="-ms-overflow-style:none;scrollbar-width:none;">
        <a href="{{ route('accessories') }}"
            class="flex-none px-4 py-2 rounded-full text-xs font-semibold {{ !request('type') ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant' }}">
            All
        </a>
        @foreach ($types as $key => $label)
            <a href="{{ route('accessories', ['type' => $key]) }}"
                class="flex-none px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap {{ request('type') === $key ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-4 px-6">
        @forelse ($accessories as $item)
            <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
                <a href="{{ route('accessory.show', $item->slug) }}" class="block aspect-square overflow-hidden bg-surface-container">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                        class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                </a>
                <div class="p-3">
                    <h3 class="font-bold text-sm text-on-surface truncate mb-0.5">{{ $item->name }}</h3>
                    <p class="text-primary font-bold text-sm mb-2">${{ number_format($item->price, 2) }}</p>
                    @if ($item->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="accessory_id" value="{{ $item->id }}" />
                            <button type="submit" class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg active:scale-95 transition-all">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <span class="block w-full text-center py-2 text-xs font-semibold text-red-500 bg-red-50 rounded-lg">Out of Stock</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-16">
                <p class="text-on-surface-variant">No accessories found.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
