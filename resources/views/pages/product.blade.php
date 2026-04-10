@extends('layouts.app')

@section('title', $bird->name . ' | Bird Haven')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* ── Main Slider ─────────────────────────────────────────── */
    .main-swiper {
        height: 589px;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .main-swiper .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: fill;
    }

    /* Custom arrows */
    .main-swiper .swiper-button-next,
    .main-swiper .swiper-button-prev {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(6px);
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        color: #0c6780;
        transition: background .2s;
    }
    .main-swiper .swiper-button-next:hover,
    .main-swiper .swiper-button-prev:hover {
        background: #fff;
    }
    .main-swiper .swiper-button-next::after,
    .main-swiper .swiper-button-prev::after {
        font-size: 14px;
        font-weight: 900;
    }

    /* ── Thumb Strip ────────────────────────────────────────── */
    .thumb-swiper {
        margin-top: 12px;
        height: 121px;
    }
    .thumb-swiper .swiper-slide {
        border-radius: 0.5rem;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        opacity: 0.6;
        transition: opacity .2s, border-color .2s;
    }
    .thumb-swiper .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: fill;
    }
    .thumb-swiper .swiper-slide-thumb-active {
        border-color: #0c6780;
        opacity: 1;
    }

    /* ── Mobile Swiper ──────────────────────────────────────── */
    .mobile-swiper {
        width: 100%;
        aspect-ratio: 4/5;
    }
    .mobile-swiper .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .mobile-swiper .swiper-pagination-bullet {
        background: #fff;
        opacity: 0.5;
        width: 6px;
        height: 6px;
    }
    .mobile-swiper .swiper-pagination-bullet-active {
        background: #fff;
        opacity: 1;
        width: 20px;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')

    {{-- ===== DESKTOP: Breadcrumb ===== --}}
    <nav class="hidden md:flex pt-32 pb-0 px-12 max-w-[1440px] mx-auto mb-8 items-center gap-2 text-sm text-on-surface-variant font-label">
        <a href="{{ route('shop') }}" class="hover:text-primary transition-colors">Species</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span>{{ $bird->category->name ?? 'Psittacines' }}</span>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-primary font-semibold">{{ $bird->name }}</span>
    </nav>

    @php
        // Always show main image first, then any additional gallery images
        $allImages = array_values(array_unique(array_filter(
            array_merge(
                $bird->image ? [$bird->image] : [],
                $bird->gallery ?? []
            )
        )));
        if (empty($allImages)) $allImages = [null];

        // Resolve each filename to a public URL
        $resolvedImages = array_map(function ($img) {
            if (!$img) return asset('assets/images/default.png');
            return file_exists(public_path('uploads/images/birds/' . $img))
                ? asset('uploads/images/birds/' . $img)
                : asset('assets/images/' . $img);
        }, $allImages);
    @endphp

    <main class="md:pb-24 md:max-w-[1440px] md:mx-auto md:px-12">

        {{-- ===== MOBILE: Hero Gallery Slider ===== --}}
        <section class="md:hidden">
            <div class="swiper mobile-swiper">
                <div class="swiper-wrapper">
                    @foreach ($resolvedImages as $src)
                        <div class="swiper-slide">
                            <img alt="{{ $bird->name }}" src="{{ $src }}"
                                 onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>

        {{-- ===== DESKTOP: Product Hero ===== --}}
        <div class="hidden md:grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            {{-- Desktop Gallery Slider --}}
            <div class="lg:col-span-7">

                {{-- Main Slider --}}
                <div class="swiper main-swiper relative">
                    <div class="swiper-wrapper">
                        @foreach ($resolvedImages as $src)
                            <div class="swiper-slide bg-surface-container-low">
                                <img src="{{ $src }}" alt="{{ $bird->name }}"
                                     onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    {{-- Badge --}}
                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase text-on-surface shadow-sm">
                            {{ $bird->badge ?? 'Rare Collection' }}
                        </span>
                    </div>
                </div>

                {{-- Thumbnail Slider --}}
                <div class="swiper thumb-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($resolvedImages as $src)
                            <div class="swiper-slide">
                                <img src="{{ $src }}" alt="{{ $bird->name }}"
                                     onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Desktop Info Panel --}}
            <div class="lg:col-span-5 sticky top-32">
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-secondary font-bold uppercase tracking-[0.2em] text-xs">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                        Ethically Sourced
                    </div>
                    <h1 class="text-5xl font-headline font-extrabold tracking-tight text-on-surface leading-tight">{{ $bird->name }}</h1>
                    <p class="text-on-surface-variant text-lg leading-relaxed max-w-md">{{ $bird->description }}</p>
                </div>
                <div class="flex flex-wrap gap-3 mb-10">
                    <div class="px-6 py-3 bg-surface-container rounded-full flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">category</span>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Species</p>
                            <p class="text-sm font-bold">{{ $bird->species ?? $bird->category->name }}</p>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-surface-container rounded-full flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">calendar_today</span>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Age</p>
                            <p class="text-sm font-bold">{{ $bird->age ?? 'Young' }}</p>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-surface-container rounded-full flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">psychology</span>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Temperament</p>
                            <p class="text-sm font-bold">{{ $bird->temperament ?? 'Highly Social' }}</p>
                        </div>
                    </div>
                </div>
                <div class="mb-10 p-8 rounded-xl bg-surface-container-low relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
                    <p class="text-sm text-on-surface-variant mb-1 font-label">Investment for Life</p>
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-headline font-black text-on-surface">${{ number_format($bird->price, 2) }}</span>
                    </div>
                    <p class="text-xs text-secondary mt-2 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-xs">local_shipping</span>
                        Includes Specialized Climate-Controlled Transport
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="col-span-2 md:col-span-1">
                        @csrf
                        <input type="hidden" name="bird_id" value="{{ $bird->id }}" />
                        <button type="submit" class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-lg shadow-[0_20px_40px_rgba(0,101,139,0.2)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">shopping_bag</span>Add to Cart
                        </button>
                    </form>
                    <form action="{{ route('wishlist.add') }}" method="POST" class="col-span-2 md:col-span-1">
                        @csrf
                        <input type="hidden" name="bird_id" value="{{ $bird->id }}" />
                        <button type="submit" class="w-full py-5 rounded-full bg-secondary-container text-on-secondary-container font-bold text-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                            Save to Wishlist
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== MOBILE: Product Header & Info ===== --}}
        <div class="md:hidden">
            <section class="px-6 pt-8 pb-4">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold tracking-widest text-primary uppercase font-headline">Species Spotlight</span>
                    <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-[10px] font-bold">ETHICALLY SOURCED</span>
                </div>
                <h1 class="text-3xl font-extrabold font-headline tracking-tighter text-on-surface mb-1">{{ $bird->name }}</h1>
                <p class="text-lg font-medium text-primary mb-4 font-headline tracking-tight">${{ number_format($bird->price, 2) }}</p>
                <p class="text-on-surface-variant leading-relaxed text-sm">{{ $bird->description }}</p>
            </section>

            {{-- Mobile Quick Info --}}
            <section class="px-6 py-4 grid grid-cols-2 gap-4">
                <div class="bg-surface-container-low p-5 rounded-xl flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-primary mb-2" style="font-variation-settings: 'FILL' 1;">timer</span>
                    <span class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-tighter">Lifespan</span>
                    <span class="text-sm font-bold text-on-surface">{{ $bird->lifespan ?? '50-60 Years' }}</span>
                </div>
                <div class="bg-surface-container-low p-5 rounded-xl flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-primary mb-2" style="font-variation-settings: 'FILL' 1;">straighten</span>
                    <span class="text-[10px] text-on-surface-variant font-semibold uppercase tracking-tighter">Wingspan</span>
                    <span class="text-sm font-bold text-on-surface">{{ $bird->wingspan ?? 'N/A' }}</span>
                </div>
            </section>

            {{-- Mobile Related Birds --}}
            @if ($relatedBirds->count())
                <section class="pt-8 pb-12">
                    <div class="px-6 flex justify-between items-end mb-6">
                        <h3 class="text-xl font-extrabold font-headline tracking-tight">Vibrant Cousins</h3>
                        <a href="{{ route('shop') }}" class="text-xs font-bold text-primary">View Archive</a>
                    </div>
                    <div class="flex gap-6 overflow-x-auto px-6" style="-ms-overflow-style:none;scrollbar-width:none;">
                        @foreach ($relatedBirds as $related)
                            <a href="{{ route('bird.show', $related->slug) }}" class="flex-none w-48">
                                <div class="h-64 rounded-xl overflow-hidden mb-3 bg-surface-container-low">
                                    <img alt="{{ $related->name }}" class="w-full h-full object-cover"
                                        src="{{ $related->image_url }}"
                                    />
                                </div>
                                <p class="font-bold text-sm tracking-tight mb-1">{{ $related->name }}</p>
                                <p class="text-xs text-on-surface-variant">${{ number_format($related->price, 2) }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- ===== DESKTOP: Care Guide ===== --}}
        <section class="hidden md:block mt-40">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-4xl font-headline font-bold text-on-surface mb-6">Mastering the Art of Care</h2>
                    <p class="text-on-surface-variant text-lg">Maintaining a {{ $bird->name }} requires dedication and specific environmental parameters. Our specialists have curated this elite guardianship guide.</p>
                </div>
                <a href="{{ route('bird.guide', $bird->slug) }}"
                   class="text-primary font-bold flex items-center gap-2 hover:gap-4 transition-all">
                    Download Full Guide <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 bg-white p-10 rounded-xl shadow-[0_20px_40px_rgba(25,28,29,0.04)] border border-outline-variant/10">
                    <div class="w-16 h-16 bg-sky-50 rounded-2xl flex items-center justify-center mb-8">
                        <span class="material-symbols-outlined text-primary text-3xl">home_health</span>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Habitat Design</h3>
                    <p class="text-on-surface-variant leading-relaxed mb-6">{{ $bird->habitat_guide ?? 'Requires a spacious aviary with structural integrity. Hardwood perches of varying diameters are essential.' }}</p>
                </div>
                <div class="md:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-secondary-container/30 p-10 rounded-xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <span class="material-symbols-outlined text-secondary text-3xl mb-8">restaurant</span>
                            <h3 class="text-xl font-bold mb-4">Artisanal Nutrition</h3>
                            <p class="text-on-secondary-container/80 leading-relaxed">{{ $bird->nutrition_guide ?? 'A balanced diet of seeds, nuts, and fresh produce is essential for optimal health.' }}</p>
                        </div>
                    </div>
                    <div class="bg-surface-container-high p-10 rounded-xl">
                        <span class="material-symbols-outlined text-on-surface text-3xl mb-8">diversity_3</span>
                        <h3 class="text-xl font-bold mb-4">Social Interaction</h3>
                        <p class="text-on-surface-variant leading-relaxed">{{ $bird->social_guide ?? 'Highly intelligent and emotional. Requires daily interaction to prevent psychological distress.' }}</p>
                    </div>
                    <div class="md:col-span-2 bg-slate-900 text-white p-10 rounded-xl flex items-center justify-between gap-12">
                        <div>
                            <h3 class="text-2xl font-bold mb-4">Specialist Consultation</h3>
                            <p class="text-slate-400 mb-6">Unsure about the environment? Schedule a virtual sanctuary walkthrough with our avian behaviorists.</p>
                            <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-slate-900 rounded-full font-bold hover:bg-sky-100 transition-colors inline-block">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== REVIEWS SECTION ===== --}}
        @php
            $approvedReviews = $bird->reviews;
            $avgRating = $approvedReviews->avg('rating');
            $totalReviews = $approvedReviews->count();
        @endphp
        <section class="mt-20 mb-4" id="reviews">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <h2 class="text-4xl font-headline font-bold text-on-surface">Guardian Reviews</h2>
                    <div class="flex items-center gap-3 mt-3">
                        @if ($totalReviews > 0)
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-xl {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}"
                                          style="font-variation-settings:'FILL' 1;">star</span>
                                @endfor
                            </div>
                            <span class="text-lg font-bold text-on-surface">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-on-surface-variant text-sm">({{ $totalReviews }} {{ Str::plural('review', $totalReviews) }})</span>
                        @else
                            <span class="text-on-surface-variant text-sm">No reviews yet — be the first!</span>
                        @endif
                    </div>
                </div>
                @auth
                    @if (!$userReview)
                        <a href="#write-review" class="px-6 py-3 bg-primary text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all text-sm">
                            Write a Review
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 border border-outline-variant/30 text-on-surface-variant font-semibold rounded-full hover:bg-surface-container transition-colors text-sm">
                        Sign in to Review
                    </a>
                @endauth
            </div>

            {{-- Existing Reviews --}}
            @if ($approvedReviews->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-14">
                    @foreach ($approvedReviews as $review)
                        <div class="bg-white rounded-xl p-7 shadow-sm border border-outline-variant/10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-on-surface">{{ $review->user->name }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-base {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"
                                              style="font-variation-settings:'FILL' 1;">star</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-on-surface-variant leading-relaxed text-sm">{{ $review->body }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Write a Review Form --}}
            @auth
                @if ($userReview)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-sm text-emerald-700 flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span>You've already submitted a review for this bird.
                            @if (!$userReview->approved) It's pending approval. @endif
                        </span>
                    </div>
                @else
                    <div id="write-review" class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 p-8">
                        <h3 class="text-xl font-bold text-on-surface mb-6">Share Your Experience</h3>

                        @if (session('review_success'))
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">check_circle</span>
                                {{ session('review_success') }}
                            </div>
                        @endif
                        @if (session('review_error'))
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm font-medium">
                                {{ session('review_error') }}
                            </div>
                        @endif
                        @if ($errors->has('rating') || $errors->has('body'))
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                @foreach ($errors->get('rating') as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                                @foreach ($errors->get('body') as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('review.store', $bird) }}" method="POST" class="space-y-6">
                            @csrf
                            {{-- Star Rating Picker --}}
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-on-surface-variant">Your Rating</label>
                                <div class="flex items-center gap-1" id="star-picker">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" data-value="{{ $i }}"
                                            class="star-btn material-symbols-outlined text-3xl text-slate-200 cursor-pointer transition-colors hover:text-amber-400"
                                            style="font-variation-settings:'FILL' 1;">star</button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}" />
                            </div>
                            {{-- Review Body --}}
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-on-surface-variant">Your Review</label>
                                <textarea name="body" rows="4" maxlength="1000"
                                    placeholder="Share your experience with this bird — temperament, settling in, what surprised you…"
                                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('body') }}</textarea>
                                <p class="text-xs text-on-surface-variant text-right" id="body-counter">0 / 1000</p>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-8 py-3 bg-primary text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div class="bg-surface-container-low rounded-xl p-8 text-center">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-3 block">rate_review</span>
                    <p class="text-on-surface font-semibold mb-1">Have this bird at home?</p>
                    <p class="text-on-surface-variant text-sm mb-4">Sign in to share your experience with the community.</p>
                    <a href="{{ route('login') }}"
                        class="inline-block px-6 py-2.5 bg-primary text-white font-bold rounded-full text-sm hover:scale-[1.02] transition-all">
                        Sign In
                    </a>
                </div>
            @endauth
        </section>

        {{-- ===== DESKTOP: Related Birds ===== --}}
        @if ($relatedBirds->count())
            <section class="hidden md:block mt-14 mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-headline font-bold text-on-surface">Other Resplendent Beings</h2>
                    <a href="{{ route('shop') }}" class="text-[11px] font-semibold text-primary hover:underline">View All</a>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($relatedBirds->take(3) as $related)
                        <a href="{{ route('bird.show', $related->slug) }}" class="group cursor-pointer">
                            <div class="aspect-square rounded-lg overflow-hidden mb-2 relative">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    alt="{{ $related->name }}"
                                    src="{{ $related->image_url }}"
                                    onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                                    <span class="text-white text-[10px] font-bold flex items-center gap-0.5">View <span class="material-symbols-outlined text-[12px]">arrow_outward</span></span>
                                </div>
                                @if($related->badge)
                                    <span class="absolute top-1.5 left-1.5 bg-white/90 backdrop-blur px-1.5 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-widest text-secondary">{{ $related->badge }}</span>
                                @endif
                            </div>
                            <h3 class="text-xs font-bold text-on-surface leading-tight mb-0.5 truncate">{{ $related->name }}</h3>
                            <p class="text-primary font-semibold text-xs">${{ number_format($related->price) }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    {{-- Mobile Sticky CTA (replaces bottom nav) --}}
    <div class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-white/95 backdrop-blur-2xl rounded-t-[3rem] shadow-[0_-10px_30px_rgba(0,0,0,0.08)] px-6 pb-8 pt-6">
        <div class="flex justify-between items-center mb-4 px-2">
            <div>
                <span class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Investment for Life</span>
                <p class="text-2xl font-black font-headline text-on-surface">${{ number_format($bird->price, 2) }}</p>
            </div>
            <span class="text-[10px] text-secondary font-bold bg-secondary-container px-2 py-0.5 rounded-full">ETHICAL GUARDIAN</span>
        </div>
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="bird_id" value="{{ $bird->id }}" />
            <button type="submit" class="block w-full py-5 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-lg rounded-full shadow-[0_10px_20px_rgba(0,101,139,0.3)] text-center">
                Reserve for Sanctuary
            </button>
        </form>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
(function () {
    // ── Star Picker ─────────────────────────────────────────────
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');

    if (stars.length) {
        // Restore old value if present
        const oldVal = parseInt(ratingInput?.value);
        if (oldVal) highlightStars(oldVal);

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => highlightStars(+star.dataset.value));
            star.addEventListener('mouseleave', () => highlightStars(+(ratingInput?.value || 0)));
            star.addEventListener('click', () => {
                ratingInput.value = star.dataset.value;
                highlightStars(+star.dataset.value);
            });
        });
    }

    function highlightStars(n) {
        stars.forEach(s => {
            s.classList.toggle('text-amber-400', +s.dataset.value <= n);
            s.classList.toggle('text-slate-200',  +s.dataset.value > n);
        });
    }

    // ── Body Counter ────────────────────────────────────────────
    const bodyTa  = document.querySelector('textarea[name="body"]');
    const counter = document.getElementById('body-counter');
    if (bodyTa && counter) {
        counter.textContent = bodyTa.value.length + ' / 1000';
        bodyTa.addEventListener('input', () => {
            counter.textContent = bodyTa.value.length + ' / 1000';
        });
    }

    // Thumbnail swiper
    const thumbSwiper = new Swiper('.thumb-swiper', {
        spaceBetween: 8,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });

    // Main swiper linked to thumbs
    new Swiper('.main-swiper', {
        spaceBetween: 0,
        navigation: {
            nextEl: '.main-swiper .swiper-button-next',
            prevEl: '.main-swiper .swiper-button-prev',
        },
        thumbs: {
            swiper: thumbSwiper,
        },
    });

    // Mobile swiper
    new Swiper('.mobile-swiper', {
        loop: true,
        pagination: {
            el: '.mobile-swiper .swiper-pagination',
            clickable: true,
        },
    });
})();
</script>
@endpush
