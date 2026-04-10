@extends('layouts.app')

@section('title', 'Bird Haven | Our Story')

@php
    $m           = $page->meta ?? [];
    $team        = $m['team'] ?? [];
    $mobileTeam  = array_slice($team, 0, 2);
    $stats       = $m['mandate_stats'] ?? [];

    $storyBanner      = !empty($m['story_banner'])
        ? asset('uploads/images/about/' . $m['story_banner'])
        : asset('assets/images/about-banner.png');

    $storyBannerChild = !empty($m['story_banner_child'])
        ? asset('uploads/images/about/' . $m['story_banner_child'])
        : asset('assets/images/about-banner-child.png');
@endphp

@section('content')

    {{-- ===== HERO ===== --}}
    {{-- Desktop --}}
    <header class="hidden md:block px-12 max-w-[1440px] mx-auto mb-24 pt-12">
        <div class="text-center max-w-4xl mx-auto">
            <span class="text-sm font-bold uppercase tracking-[0.2em] text-primary mb-4 block">Our Story</span>
            <h1 class="text-7xl font-extrabold tracking-tighter text-on-surface leading-[1.1] mb-8">
                {{ $m['hero_headline'] ?? 'Elevating the Standard of Avian Stewardship' }}
            </h1>
            <p class="text-xl text-on-surface-variant leading-relaxed">
                {{ $m['hero_intro'] ?? '' }}
            </p>
        </div>
    </header>

    {{-- Mobile Hero --}}
    <header class="md:hidden text-center px-6 mb-16 pt-4">
        <span class="text-primary font-semibold tracking-widest uppercase text-xs mb-4 block">Our Legacy</span>
        <h1 class="text-4xl font-extrabold tracking-tight text-on-surface leading-tight mb-6">
            {{ $m['mobile_hero_headline'] ?? 'Wings of Change' }}
        </h1>
        <p class="text-lg text-on-surface-variant leading-relaxed">
            {{ $m['mobile_hero_intro'] ?? '' }}
        </p>
    </header>

    {{-- ===== DESKTOP: Legacy / Story Section ===== --}}
    <section class="hidden md:block mb-32 px-12 max-w-[1440px] mx-auto">
        <div class="relative grid grid-cols-12 gap-8 items-center bg-surface-container-low rounded-xl p-12 overflow-visible">
            <div class="col-span-12 lg:col-span-5 z-10">
                <h2 class="text-4xl font-bold tracking-tight mb-6">
                    {{ $m['story_title'] ?? 'A Legacy in Flight' }}
                </h2>
                <p class="text-lg text-on-surface-variant mb-6 leading-relaxed">
                    {{ $m['story_body_1'] ?? '' }}
                </p>
                <p class="text-lg text-on-surface-variant leading-relaxed">
                    {{ $m['story_body_2'] ?? '' }}
                </p>
            </div>
            <div class="col-span-12 lg:col-span-7 relative">
                <div class="relative aspect-video rounded-xl overflow-hidden shadow-2xl">
                    <img class="w-full h-full object-cover" alt="About banner"
                         src="{{ $storyBanner }}"
                         onerror="this.src='{{ asset('assets/images/about-banner.png') }}'" />
                </div>
                <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-white p-4 rounded-xl shadow-xl hidden lg:block">
                    <img class="w-full h-full object-cover rounded-lg" alt="About detail"
                         src="{{ $storyBannerChild }}"
                         onerror="this.src='{{ asset('assets/images/about-banner-child.png') }}'" />
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MOBILE: Feature Float Image ===== --}}
    <div class="md:hidden relative px-6 mb-20">
        <div class="absolute -top-10 -left-4 w-64 h-64 bg-secondary-container/30 rounded-full blur-3xl -z-10"></div>
        <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm">
            <img alt="About image" class="w-full h-72 object-cover rounded-lg shadow-xl -mt-16 transform rotate-2 hover:rotate-0 transition-transform duration-500"
                src="{{ asset('assets/images/about-banner-mobile.png') }}" />
            <div class="mt-8 text-center">
                <h3 class="text-2xl font-bold text-on-surface mb-2">Ethical Guardianship</h3>
                <p class="text-on-surface-variant">Our sanctuary isn't just a place; it's a philosophy of coexistence and profound respect for avian intelligence.</p>
            </div>
        </div>
    </div>

    {{-- ===== DESKTOP: Ethical Mandate ===== --}}
    <section class="hidden md:block bg-secondary-container/30 py-32 mb-32">
        <div class="max-w-[1440px] mx-auto px-12 text-center">
            <div class="max-w-3xl mx-auto">
                <span class="material-symbols-outlined text-secondary text-5xl mb-6" style="font-variation-settings: 'FILL' 1;">eco</span>
                <h2 class="text-4xl font-bold tracking-tight text-on-secondary-container mb-8">The Ethical Mandate</h2>
                <p class="text-2xl text-on-secondary-container/80 leading-[1.4] font-light">
                    "{{ $m['mandate_quote'] ?? '' }}"
                </p>
                @if(!empty($stats))
                <div class="mt-12 flex justify-center gap-12">
                    @foreach($stats as $stat)
                        <div class="text-left">
                            <h4 class="text-3xl font-black text-secondary">{{ $stat['value'] }}</h4>
                            <p class="text-sm font-medium text-on-secondary-container uppercase tracking-wider">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== MOBILE: Care Philosophy Bento ===== --}}
    <section class="md:hidden px-6 space-y-6 mb-20">
        <div class="bg-surface-container-low p-8 rounded-lg">
            <span class="material-symbols-outlined text-4xl text-primary mb-4">nest_eco</span>
            <h4 class="text-xl font-bold mb-2">Natural Habitats</h4>
            <p class="text-on-surface-variant text-sm">We recreate micro-climates that mimic the wild origins of every species in our care.</p>
        </div>
        <div class="bg-secondary-container/40 p-8 rounded-lg">
            <span class="material-symbols-outlined text-4xl text-secondary mb-4">healing</span>
            <h4 class="text-xl font-bold mb-2">Holistic Wellness</h4>
            <p class="text-on-secondary-container text-sm">Beyond medical care, we prioritize the emotional and social needs of our feathered residents.</p>
        </div>
        <div class="bg-primary-container/20 p-8 rounded-lg">
            <span class="material-symbols-outlined text-4xl text-primary mb-4">school</span>
            <h4 class="text-xl font-bold mb-2">Educational Roots</h4>
            <p class="text-on-primary-container text-sm">Empowering guardians with the knowledge to provide a lifetime of avian joy and health.</p>
        </div>
    </section>

    {{-- ===== DESKTOP: Team Section ===== --}}
    <section class="hidden md:block px-12 max-w-[1440px] mx-auto mb-32">
        <div class="flex justify-between items-end mb-16">
            <div>
                <span class="text-sm font-bold uppercase tracking-[0.2em] text-primary mb-4 block">The Guardians</span>
                <h2 class="text-5xl font-extrabold tracking-tight">Our Specialist Team</h2>
            </div>
            <p class="text-on-surface-variant max-w-sm">A collective of biologists, behaviorists, and master artisans working in tandem.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @forelse($team as $i => $member)
                @php
                    $photo = !empty($member['image'])
                        ? asset('uploads/images/team/' . $member['image'])
                        : asset('assets/images/team-' . ($i + 1) . '.png');
                @endphp
                <div class="group">
                    <div class="aspect-[4/5] rounded-xl overflow-hidden mb-6 relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <img class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-100 group-hover:scale-110"
                            alt="{{ $member['name'] }}"
                            src="{{ $photo }}"
                            onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                    </div>
                    <h3 class="text-2xl font-bold mb-1">{{ $member['name'] }}</h3>
                    <p class="text-primary font-medium text-sm mb-4">{{ $member['role'] }}</p>
                    <p class="text-on-surface-variant leading-relaxed">{{ $member['bio'] }}</p>
                </div>
            @empty
                <p class="col-span-3 text-on-surface-variant text-center py-8">No team members added yet.</p>
            @endforelse
        </div>
    </section>

    {{-- ===== MOBILE: Team Section ===== --}}
    <section class="md:hidden px-6 mb-20">
        <h2 class="text-3xl font-extrabold text-center mb-12">The Guardians</h2>
        <div class="space-y-12">
            @foreach($mobileTeam as $i => $member)
                @php
                    $photo = !empty($member['image'])
                        ? asset('uploads/images/team/' . $member['image'])
                        : asset('assets/images/team-' . ($i + 1) . '.png');
                @endphp
                <div class="flex flex-col items-center text-center">
                    <div class="w-48 h-48 rounded-full overflow-hidden mb-6 ring-4 ring-surface-container-high ring-offset-4">
                        <img alt="{{ $member['name'] }}" class="w-full h-full object-cover"
                             src="{{ $photo }}"
                             onerror="this.src='{{ asset('assets/images/default.png') }}'" />
                    </div>
                    <h5 class="text-xl font-bold text-on-surface">{{ $member['name'] }}</h5>
                    <p class="text-primary font-medium text-sm mb-3">{{ $member['role'] }}</p>
                    <p class="text-on-surface-variant text-sm px-4">{{ $member['bio'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== MOBILE: Quote ===== --}}
    <section class="md:hidden mx-6 bg-surface-container-highest rounded-xl p-10 text-center italic mb-20">
        <span class="material-symbols-outlined text-primary-container text-6xl opacity-50 block mb-4">format_quote</span>
        <p class="text-xl font-medium text-on-surface leading-relaxed">"To care for a bird is to witness the sky in your own home. We make sure that witness is worthy of their majesty."</p>
    </section>

    {{-- ===== CTA ===== --}}
    {{-- Desktop --}}
    <section class="hidden md:block px-12 max-w-[1440px] mx-auto mb-20">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-primary-container p-20 text-center">
            <div class="relative z-10">
                <h2 class="text-5xl font-black text-white mb-6 tracking-tight">
                    {{ $m['cta_title'] ?? 'Join the Sanctuary Community' }}
                </h2>
                <p class="text-white/80 text-xl mb-10 max-w-2xl mx-auto">
                    {{ $m['cta_subtitle'] ?? '' }}
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('shop') }}" class="bg-white text-primary px-10 py-4 rounded-full font-bold shadow-lg hover:scale-105 transition-transform">Explore The Archive</a>
                    <a href="{{ route('contact') }}" class="bg-primary-container/20 border border-white/30 text-white backdrop-blur-md px-10 py-4 rounded-full font-bold hover:bg-white/10 transition-colors">Contact A Specialist</a>
                </div>
            </div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-secondary-container/20 rounded-full blur-3xl"></div>
        </div>
    </section>

    {{-- Mobile CTA --}}
    <section class="md:hidden px-6 mb-20">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-primary-container p-10 text-center">
            <div class="relative z-10">
                <h2 class="text-3xl font-black text-white mb-4 tracking-tight">
                    {{ $m['cta_title'] ?? 'Join the Community' }}
                </h2>
                <p class="text-white/80 text-base mb-8">
                    {{ $m['cta_subtitle'] ?? '' }}
                </p>
                <a href="{{ route('shop') }}" class="block bg-white text-primary px-8 py-4 rounded-full font-bold shadow-lg w-full hover:scale-105 transition-transform">Explore The Archive</a>
            </div>
            <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>
    </section>

@endsection
