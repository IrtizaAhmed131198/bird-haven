@props(['bird'])

<div class="bg-surface-container-lowest rounded-xl p-4 ethereal-shadow group">
    <div class="relative rounded-lg overflow-hidden mb-6 h-64">
        <img alt="{{ $bird->name }}"
            class="w-full h-full object-fill group-hover:scale-110 transition-transform duration-700"
            src="{{ $bird->image_url }}"
            loading="lazy"
            onerror="this.src='{{ asset('assets/images/default.png') }}'" />
        @if($bird->badge)
            <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-primary uppercase tracking-widest">
                {{ $bird->badge }}
            </span>
        @endif
        <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-md p-2 rounded-full hover:bg-white transition-colors">
            <span class="material-symbols-outlined text-sm">favorite</span>
        </button>
    </div>
    <div class="px-2">
        <div class="flex justify-between items-start mb-2">
            <div>
                <h3 class="font-bold text-lg leading-tight">{{ $bird->name }}</h3>
                <p class="text-sm text-on-surface-variant">{{ $bird->subtitle ?? $bird->category?->name }}</p>
            </div>
            <span class="text-primary font-bold text-lg">${{ number_format($bird->price) }}</span>
        </div>
        @if($bird->species)
            <p class="text-xs italic text-on-surface-variant mb-3">{{ $bird->species }}</p>
        @endif
        <a href="{{ route('bird.show', $bird->slug) }}"
            class="block w-full mt-4 bg-surface-container-high hover:bg-primary hover:text-white py-3 rounded-lg font-semibold transition-all duration-300 text-center">
            View Heritage
        </a>
    </div>
</div>
