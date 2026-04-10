@extends('layouts.app')

@section('title', 'Bird Haven | Ethical Avian Guardianship')

@section('content')

    {{-- ===== HERO ===== --}}

    {{-- Desktop Hero --}}
    <header class="hidden md:block pt-8 pb-12 px-12 max-w-[1440px] mx-auto">
        <div class="relative h-[800px] rounded-xl overflow-hidden group">
            <img alt="Hero Banner"
                class="w-full h-full object-fill transform group-hover:scale-105 transition-transform duration-1000"
                src="{{ $hero['image'] }}"
                onerror="this.src='{{ asset('assets/images/banner.png') }}'" />
            <div class="absolute inset-0 bg-gradient-to-r from-on-surface/60 via-transparent to-transparent flex flex-col justify-center px-20">
                <span class="text-primary-fixed uppercase tracking-[0.3em] font-bold text-sm mb-6">Established 1984</span>
                <h1 class="font-headline text-7xl font-extrabold text-white leading-[1.1] max-w-2xl mb-8">
                    {!! preg_replace('/\[highlight\](.*?)\[\/highlight\]/', '<span class="text-primary-container">$1</span>', e($hero['title'])) !!}
                </h1>
                <p class="text-surface-container-low text-xl max-w-lg mb-10 leading-relaxed">
                    {{ $hero['subtitle'] }}
                </p>
                <div class="flex gap-4 items-center mb-12">
                    <a href="{{ route('shop') }}" class="bg-gradient-primary text-white px-10 py-5 rounded-full font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all">
                        Explore Species
                    </a>
                    <a href="{{ route('about') }}" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-10 py-5 rounded-full font-semibold hover:bg-white/20 transition-all">
                        Our Story
                    </a>
                </div>
                {{-- Live stats strip --}}
                <div class="flex gap-10">
                    <div>
                        <span class="block text-3xl font-extrabold text-white">{{ $stats['birds'] }}+</span>
                        <span class="text-white/60 text-xs uppercase tracking-widest font-bold">Birds Available</span>
                    </div>
                    <div class="w-px bg-white/20"></div>
                    <div>
                        <span class="block text-3xl font-extrabold text-white">{{ $stats['categories'] }}</span>
                        <span class="text-white/60 text-xs uppercase tracking-widest font-bold">Species Groups</span>
                    </div>
                    <div class="w-px bg-white/20"></div>
                    <div>
                        <span class="block text-3xl font-extrabold text-white">{{ $stats['in_stock'] }}</span>
                        <span class="text-white/60 text-xs uppercase tracking-widest font-bold">In Stock Today</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Hero --}}
    <section class="md:hidden px-6 mb-10 pt-4">
        <div class="relative h-[500px] w-full rounded-xl overflow-hidden shadow-2xl group">
            <img alt="Hero Banner"
                class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105"
                src="{{ $hero['image'] }}"
                onerror="this.src='{{ asset('assets/images/banner.png') }}'" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent flex flex-col justify-end p-8">
                <span class="text-primary-container font-headline font-bold tracking-widest text-xs uppercase mb-3">
                    {{ $stats['birds'] }}+ Birds · {{ $stats['categories'] }} Collections
                </span>
                <h2 class="text-4xl font-headline font-extrabold text-white leading-tight mb-4">Majesty of the High Canopy</h2>
                <p class="text-white/80 text-sm mb-6 max-w-[280px]">Experience the rare elegance of our newest sanctuary inhabitants from the cloud forests.</p>
                <a href="{{ route('shop') }}" class="bg-gradient-to-r from-primary to-primary-container text-white font-semibold py-4 px-8 rounded-full shadow-lg self-start active:scale-95 transition-transform">
                    Explore Sanctuary
                </a>
            </div>
        </div>
    </section>

    {{-- Mobile Search --}}
    <section class="md:hidden px-6 mb-10">
        <div class="relative flex items-center">
            <div class="absolute left-4 text-slate-400"><span class="material-symbols-outlined">search</span></div>
            <input class="w-full bg-white border-none rounded-xl py-5 pl-12 pr-4 shadow-[0_10px_30px_rgba(0,0,0,0.02)] focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-slate-400 outline-none"
                placeholder="Find your avian companion..." type="text" />
        </div>
    </section>

    {{-- Mobile Category Quick Access --}}
    @if($categories->isNotEmpty())
    <section class="md:hidden px-6 mb-12">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline font-bold text-xl tracking-tight">Explore Species</h3>
            <a href="{{ route('shop') }}" class="text-primary font-semibold text-sm">View All</a>
        </div>
        <div class="flex gap-6 overflow-x-auto no-scrollbar pb-2">
            @php
                $mobilePalettes = [
                    ['bg' => 'bg-secondary-container',        'color' => 'text-on-secondary-container'],
                    ['bg' => 'bg-primary-fixed',              'color' => 'text-on-primary-fixed-variant'],
                    ['bg' => 'bg-tertiary-fixed',             'color' => 'text-on-tertiary-fixed-variant'],
                    ['bg' => 'bg-surface-container-highest',  'color' => 'text-on-surface-variant'],
                    ['bg' => 'bg-primary-container',          'color' => 'text-on-primary-container'],
                    ['bg' => 'bg-tertiary-container',         'color' => 'text-on-tertiary-container'],
                ];
            @endphp
            @foreach($categories->take(6) as $i => $cat)
                @php $palette = $mobilePalettes[$i % count($mobilePalettes)]; @endphp
                <a href="{{ route('shop', ['category' => $cat->id]) }}" class="flex flex-col items-center gap-3 shrink-0">
                    <div class="w-20 h-20 rounded-full {{ $palette['bg'] }} overflow-hidden flex items-center justify-center shadow-sm">
                        @if($cat->image)
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                            <span class="material-symbols-outlined {{ $palette['color'] }} text-3xl">category</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-600 text-center max-w-[72px] leading-tight">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ===== DESKTOP: Category Bento Grid ===== --}}
    @if($categories->isNotEmpty())
    <section class="hidden md:block py-20 px-12 max-w-[1440px] mx-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="font-headline text-4xl font-bold mb-2">Curated Collections</h2>
                <p class="text-on-surface-variant">Find the perfect companion or care essentials.</p>
            </div>
            <a href="{{ route('shop') }}" class="text-primary font-bold hover:underline flex items-center gap-1">
                View All <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">

            {{-- Featured large card: first/biggest category --}}
            @php $featuredCat = $categories->first(); @endphp
            <div class="md:col-span-2 lg:col-span-3 bg-secondary-container rounded-xl p-8 flex flex-col justify-between relative overflow-hidden group min-h-[220px]">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold text-on-secondary-container mb-2">{{ $featuredCat->name }}</h3>
                    <p class="text-on-secondary-container/80 max-w-xs">
                        {{ $featuredCat->description ?? 'Discover our exquisite collection of ' . strtolower($featuredCat->name) . '.' }}
                    </p>
                    <a href="{{ route('shop', ['category' => $featuredCat->id]) }}"
                       class="mt-6 text-on-secondary-container font-bold flex items-center gap-2 group-hover:gap-4 transition-all">
                        Discover <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
                @if($featuredCat->image)
                    <img alt="{{ $featuredCat->name }}"
                        class="absolute right-[-10%] bottom-[-10%] w-1/2 h-full object-cover opacity-20 group-hover:opacity-30 transition-opacity duration-700"
                        src="{{ $featuredCat->image_url }}"
                        loading="lazy" />
                @else
                    <img alt="Collection"
                        class="absolute right-[-20%] bottom-[-10%] w-2/3 object-contain transform group-hover:scale-110 transition-transform duration-700"
                        src="{{ asset('assets/images/collection.png') }}"
                        loading="lazy" />
                @endif
                <span class="absolute top-6 right-6 bg-on-secondary-container/10 text-on-secondary-container text-xs font-bold px-3 py-1 rounded-full">
                    {{ $featuredCat->birds_count }} birds
                </span>
            </div>

            {{-- Secondary categories: slots 2–5 --}}
            @php
                $bentoPalettes = [
                    ['bg' => 'hover:bg-primary-container',  'icon_color' => 'text-primary'],
                    ['bg' => 'hover:bg-tertiary-container', 'icon_color' => 'text-tertiary'],
                    ['bg' => 'hover:bg-secondary-container','icon_color' => 'text-secondary'],
                    ['bg' => 'hover:bg-primary-fixed',      'icon_color' => 'text-primary'],
                ];
            @endphp
            @foreach($categories->skip(1)->take(4) as $j => $cat)
                @php $p = $bentoPalettes[$j % 4]; @endphp
                <a href="{{ route('shop', ['category' => $cat->id]) }}"
                   class="bg-surface-container-high rounded-xl p-6 flex flex-col items-center justify-center text-center group {{ $p['bg'] }} transition-colors duration-500">
                    <div class="w-16 h-16 rounded-full bg-white mb-4 ethereal-shadow group-hover:scale-110 transition-transform overflow-hidden flex items-center justify-center">
                        @if($cat->image)
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-fill" loading="lazy"
                                onerror="this.onerror=null; this.src='{{ asset('assets/images/default.png') }}';">
                        @else
                            <span class="material-symbols-outlined {{ $p['icon_color'] }} scale-150">category</span>
                        @endif
                    </div>
                    <span class="font-bold text-sm">{{ $cat->name }}</span>
                    <span class="text-[10px] text-on-surface-variant mt-1">{{ $cat->birds_count }} birds</span>
                </a>
            @endforeach

            {{-- 6th slot: View All --}}
            <div class="lg:col-span-1 bg-surface-container-low rounded-xl p-6 flex flex-col items-center justify-center group">
                <span class="material-symbols-outlined text-4xl mb-2 text-outline group-hover:text-primary transition-colors">more_horiz</span>
                <a href="{{ route('shop') }}" class="text-sm font-semibold hover:text-primary transition-colors">View All</a>
            </div>

        </div>
    </section>
    @endif

    {{-- ===== FEATURED BIRDS (Desktop) ===== --}}
    <section class="hidden md:block bg-surface-container-low py-24 px-12">
        <div class="max-w-[1440px] mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-headline text-5xl font-extrabold mb-4">Featured Residents</h2>
                <div class="w-24 h-1 bg-gradient-primary mx-auto rounded-full"></div>
            </div>

            @if($featuredBirds->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                    @foreach($featuredBirds as $bird)
                        <x-product-card :bird="$bird" />
                    @endforeach
                </div>
            @else
                {{-- Fallback: show newest active birds --}}
                @php
                    $latestBirds = \App\Models\Bird::where('is_active', true)->with('category')->latest()->take(4)->get();
                @endphp
                @if($latestBirds->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                        @foreach($latestBirds as $bird)
                            <x-product-card :bird="$bird" />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4 block">flutter_dash</span>
                        <p class="text-on-surface-variant font-medium">No birds available yet. <a href="{{ route('shop') }}" class="text-primary underline">Browse shop</a></p>
                    </div>
                @endif
            @endif

            <div class="text-center mt-14">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center gap-2 border-2 border-primary text-primary px-10 py-4 rounded-full font-bold hover:bg-primary hover:text-white transition-all duration-300">
                    View All Birds <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Mobile Featured Collections --}}
    <section class="md:hidden px-6 mb-12">
        <h3 class="font-headline font-bold text-xl tracking-tight mb-6">Featured Collections</h3>

        @if($mobileFeatured->isNotEmpty())
            <div class="grid grid-cols-1 gap-8">
                @foreach($mobileFeatured as $bird)
                    <a href="{{ route('bird.show', $bird->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.04)] block">
                        <div class="h-[280px] w-full overflow-hidden relative">
                            <img alt="{{ $bird->name }}" class="w-full h-full object-cover"
                                src="{{ $bird->image_url }}"
                                loading="lazy"
                                onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                            @if($bird->badge)
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-primary uppercase tracking-widest">
                                    {{ $bird->badge }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-headline font-bold text-lg text-slate-900">{{ $bird->name }}</h4>
                                <span class="text-primary font-bold">${{ number_format($bird->price) }}</span>
                            </div>
                            <p class="text-slate-500 text-sm mb-4 leading-relaxed line-clamp-2">
                                {{ $bird->description ?? $bird->subtitle ?? 'A magnificent avian companion.' }}
                            </p>
                            <div class="flex gap-2 flex-wrap">
                                @if($bird->category)
                                    <span class="bg-slate-100 px-3 py-1 rounded-md text-[10px] font-bold text-slate-500 uppercase">{{ $bird->category->name }}</span>
                                @endif
                                @if($bird->temperament)
                                    <span class="bg-slate-100 px-3 py-1 rounded-md text-[10px] font-bold text-slate-500 uppercase">{{ $bird->temperament }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-on-surface-variant text-sm text-center py-8">
                No featured birds yet. <a href="{{ route('shop') }}" class="text-primary underline">Browse all birds</a>
            </p>
        @endif
    </section>

    {{-- ===== TESTIMONIALS (Desktop only) ===== --}}
    <section class="hidden md:block py-24 px-12 max-w-[1440px] mx-auto overflow-hidden">
        <div class="flex flex-col md:flex-row gap-20 items-center">
            <div class="w-full md:w-1/2 relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-secondary-container/30 rounded-full blur-3xl"></div>
                <div class="relative z-10 p-12 bg-white rounded-xl ethereal-shadow">
                    <span class="material-symbols-outlined text-6xl text-primary/20 mb-6" style="font-variation-settings:'FILL' 1">format_quote</span>
                    <p class="text-2xl font-medium leading-relaxed mb-10 text-on-surface">
                        "The level of expertise and care at Bird Haven is unparalleled. They didn't just sell me a bird; they introduced me to a new family member and provided a lifetime of support."
                    </p>
                    <div class="flex items-center gap-4">
                        <img alt="Customer Profile" class="w-16 h-16 rounded-full object-cover"
                            src="{{ asset('assets/images/user.png') }}"
                            loading="lazy" />
                        <div>
                            <h4 class="font-bold text-lg">Elena Richardson</h4>
                            <p class="text-on-surface-variant text-sm">Avian Enthusiast since 2012</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2">
                <span class="text-primary uppercase tracking-widest font-bold text-sm mb-4 block">The Community</span>
                <h2 class="font-headline text-5xl font-extrabold mb-8">Trusted by Guardians Worldwide</h2>
                <p class="text-on-surface-variant text-lg mb-10 leading-relaxed">
                    We believe in ethical guardianship. Every bird in our care is raised with the utmost attention to psychological and physical health, ensuring a seamless transition to their forever homes.
                </p>
                <div class="flex gap-12">
                    <div>
                        <span class="block text-4xl font-extrabold text-primary mb-1">{{ $stats['birds'] }}+</span>
                        <span class="text-sm font-semibold text-outline uppercase tracking-wider">Birds Available</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-extrabold text-secondary mb-1">{{ $stats['categories'] }}</span>
                        <span class="text-sm font-semibold text-outline uppercase tracking-wider">Collections</span>
                    </div>
                    <div>
                        <span class="block text-4xl font-extrabold text-tertiary mb-1">{{ $stats['in_stock'] }}</span>
                        <span class="text-sm font-semibold text-outline uppercase tracking-wider">In Stock</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ACCESSORIES (Desktop) ===== --}}
    <section class="hidden md:block py-24 px-12 bg-white">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex justify-between items-end mb-12">
                <h2 class="font-headline text-4xl font-bold">Essential Accoutrements</h2>
                <a class="text-primary font-bold hover:underline" href="{{ route('shop') }}">Shop All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($accessories as $item)
                    <div class="flex gap-6 items-center p-6 bg-surface-container rounded-xl group hover:bg-surface-container-high transition-colors">
                        <a href="{{ route('accessory.show', $item->slug) }}" class="w-32 h-32 rounded-lg bg-white overflow-hidden flex-shrink-0 block">
                            <img alt="{{ $item->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform"
                                src="{{ $item->image_url }}"
                                loading="lazy"
                                onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('accessory.show', $item->slug) }}">
                                <h3 class="font-bold text-lg mb-1 hover:text-primary transition-colors">{{ $item->name }}</h3>
                            </a>
                            <p class="text-sm text-on-surface-variant mb-3 line-clamp-2">{{ $item->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-primary">${{ number_format($item->price, 2) }}</span>
                                @if ($item->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="accessory_id" value="{{ $item->id }}" />
                                        <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-full hover:scale-105 active:scale-95 transition-all shadow-md shadow-primary/20">
                                            Add to Cart
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-on-surface-variant py-8">No featured accessories available.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Mobile Newsletter --}}
    <section class="md:hidden mx-6 p-8 rounded-xl bg-secondary-container/30 mb-12">
        <h3 class="font-headline font-bold text-xl mb-3">Avian Care Journal</h3>
        <p class="text-slate-600 text-sm mb-6 leading-relaxed">Join our circle of ethical guardians. Receive weekly insights on avian health, nutrition, and sanctuary updates.</p>
        @if (session('newsletter_success'))
            <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                {{ session('newsletter_success') }}
            </div>
        @endif
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col gap-3">
            @csrf
            <input name="email" type="email" required placeholder="Your email address"
                class="w-full bg-white/60 border-none rounded-xl py-4 px-4 text-sm focus:ring-secondary/20 outline-none" />
            <button type="submit" class="bg-secondary text-white font-bold py-4 rounded-xl active:scale-95 transition-transform">Subscribe to Journal</button>
        </form>
    </section>

@endsection
