@extends('layouts.app')

@section('title', 'Wishlist | Bird Haven')

@section('content')

    {{-- ===== DESKTOP MAIN ===== --}}
    <div class="hidden md:block pt-32 pb-24 px-12 max-w-[1440px] mx-auto">
        <header class="mb-16">
            <div class="flex items-center space-x-3 mb-4">
                <span class="h-[1px] w-12 bg-primary"></span>
                <span class="text-sm font-bold uppercase tracking-[0.2em] text-primary">Your Sanctuary</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-on-surface mb-4">The Wishlist</h1>
            <p class="text-lg text-on-surface-variant max-w-2xl leading-relaxed">A curated collection of your future companions and the ethical essentials required for their flourish.</p>
        </header>

        @if ($wishlistItems->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-4xl text-outline-variant">flutter_dash</span>
                </div>
                <h4 class="text-xl font-headline font-bold">Your Sky is Empty</h4>
                <p class="text-on-surface-variant mt-2 max-w-[240px]">Start adding your favorite ethical companions to your wishlist.</p>
                <a href="{{ route('shop') }}" class="mt-6 text-primary font-bold hover:underline">Explore the Archive</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ($wishlistItems as $item)
                    <div class="group bg-surface-container-lowest rounded-xl p-6 shadow-[0_20px_40px_rgba(25,28,29,0.06)] hover:-translate-y-2 transition-all duration-500">
                        <div class="relative aspect-[4/5] rounded-lg overflow-hidden mb-8">
                            <img class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $item->bird->name }}" src="{{ $item->bird->image_url }}" />
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="absolute top-4 right-4 bg-white/90 backdrop-blur shadow-sm p-3 rounded-full text-error hover:bg-error hover:text-white transition-all duration-300">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
                        </div>
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-on-surface mb-1">{{ $item->bird->name }}</h3>
                                <p class="text-sm text-secondary font-semibold uppercase tracking-wider">{{ $item->bird->subtitle }}</p>
                            </div>
                            <span class="text-xl font-bold text-primary">${{ number_format($item->bird->price) }}</span>
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bird_id" value="{{ $item->bird->id }}" />
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-white font-bold rounded-full shadow-lg hover:shadow-primary/30 transition-all active:scale-95 flex items-center justify-center space-x-2">
                                <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                                <span>Add to Cart</span>
                            </button>
                        </form>
                    </div>
                @endforeach

                {{-- "Find More" suggestion card --}}
                <div class="bg-secondary-container/30 rounded-xl p-8 border-2 border-dashed border-secondary/20 flex flex-col items-center justify-center text-center">
                    <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center mb-6 shadow-sm">
                        <span class="material-symbols-outlined text-secondary text-3xl">flutter_dash</span>
                    </div>
                    <h3 class="text-xl font-bold text-on-secondary-container mb-2">Find More Companions</h3>
                    <p class="text-sm text-on-secondary-container/70 mb-6">Browse our newly arriving species from ethically certified sanctuaries.</p>
                    <a href="{{ route('shop') }}" class="text-secondary font-bold hover:underline decoration-2 underline-offset-4 transition-all">Explore Sanctuary</a>
                </div>
            </div>
        @endif
    </div>

    {{-- ===== MOBILE MAIN ===== --}}
    <div class="md:hidden pt-24 pb-32 px-6 max-w-md mx-auto min-h-screen">
        <div class="mb-8">
            <span class="text-primary font-headline font-bold text-xs uppercase tracking-widest">Your Sanctuary</span>
            <h2 class="text-3xl font-headline font-extrabold text-on-surface tracking-tight mt-1">Wishlist</h2>
            <p class="text-on-surface-variant mt-2 text-sm">Review the majestic creatures you've curated for your future guardianship.</p>
        </div>
        <div class="flex flex-col gap-6">
            @forelse ($wishlistItems as $item)
                <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-outline-variant/10">
                    <div class="relative h-64 overflow-hidden">
                        <img class="w-full h-full object-cover transition-transform duration-700 hover:scale-110"
                            alt="{{ $item->bird->name }}" src="{{ $item->bird->image_url }}" />
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md p-2 rounded-full shadow-sm">
                            <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-xl font-headline font-bold text-on-surface">{{ $item->bird->name }}</h3>
                                <p class="text-sm text-secondary font-medium">{{ $item->bird->subtitle }}</p>
                            </div>
                            <span class="text-lg font-headline font-extrabold text-primary">${{ number_format($item->bird->price) }}</span>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="bird_id" value="{{ $item->bird->id }}" />
                                <button type="submit" class="w-full bg-gradient-to-br from-primary to-primary-container text-white font-bold py-3.5 px-6 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                                    <span class="material-symbols-outlined text-sm">shopping_cart</span>
                                    Add to Aviary
                                </button>
                            </form>
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-14 h-14 bg-surface-container-high text-on-surface-variant flex items-center justify-center rounded-xl hover:bg-error/10 hover:text-error transition-colors">
                                    <span class="material-symbols-outlined">delete_outline</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-4xl text-outline-variant">flutter_dash</span>
                    </div>
                    <h4 class="text-xl font-headline font-bold">Your Sky is Empty</h4>
                    <p class="text-on-surface-variant mt-2 max-w-[240px]">Start adding your favorite ethical companions to your wishlist.</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection
