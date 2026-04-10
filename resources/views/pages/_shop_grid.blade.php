{{-- Bird cards — rendered for both initial load and AJAX reloads --}}

{{-- Hidden meta for JS to read count info --}}
<div id="grid-meta"
     data-total="{{ $birds->total() }}"
     data-from="{{ $birds->firstItem() ?? 0 }}"
     data-to="{{ $birds->lastItem() ?? 0 }}">
</div>

{{-- Cards --}}
@forelse ($birds as $bird)
<div class="bird-card group cursor-pointer">

    {{-- Image --}}
    <div class="card-image relative overflow-hidden rounded-xl bg-surface-container-low aspect-[3/4] mb-3">
        <img alt="{{ $bird->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
             src="{{ $bird->image_url }}"
             loading="lazy"
             onerror="this.src='{{ asset('assets/images/default.png') }}'" />

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($bird->badge)
                <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-secondary shadow-sm">{{ $bird->badge }}</span>
            @endif
            @if($bird->featured)
                <span class="bg-primary text-on-primary px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest shadow">Featured</span>
            @endif
        </div>

        {{-- Wishlist --}}
        @auth
        <form action="{{ route('wishlist.add') }}" method="POST"
              class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
            @csrf
            <input type="hidden" name="bird_id" value="{{ $bird->id }}" />
            <button type="submit"
                class="w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center shadow-lg hover:text-error transition-colors">
                <span class="material-symbols-outlined text-[18px]">favorite</span>
            </button>
        </form>
        @endauth
    </div>

    {{-- Body --}}
    <div class="card-body space-y-1">
        <div class="flex justify-between items-start gap-2">
            <h3 class="text-base font-headline font-bold text-on-surface leading-tight">{{ $bird->name }}</h3>
            <div class="text-right shrink-0">
                <span class="text-base font-bold text-primary">${{ number_format($bird->price) }}</span>
                @if($bird->original_price)
                    <span class="block text-[10px] text-on-surface-variant line-through">${{ number_format($bird->original_price) }}</span>
                @endif
            </div>
        </div>
        <p class="text-xs text-on-surface-variant line-clamp-1">{{ $bird->subtitle }}</p>

        {{-- List-view extra detail (hidden in grid) --}}
        <p class="list-only text-xs text-on-surface-variant line-clamp-2 hidden">{{ $bird->description }}</p>
        <div class="list-only hidden flex-wrap gap-3 text-xs text-on-surface-variant">
            @if($bird->species) <span><strong>Species:</strong> {{ $bird->species }}</span> @endif
            @if($bird->age)     <span><strong>Age:</strong> {{ $bird->age }}</span> @endif
            @if($bird->color)   <span><strong>Colour:</strong> {{ ucfirst($bird->color) }}</span> @endif
        </div>

        <div class="flex gap-2 pt-2">
            <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="bird_id" value="{{ $bird->id }}" />
                <button type="submit"
                    class="w-full bg-gradient-to-r from-primary to-primary-container text-on-primary py-2 rounded-full font-bold text-xs shadow hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    Add to Cart
                </button>
            </form>
            <a href="{{ route('bird.show', $bird->slug) }}"
               class="w-9 h-9 rounded-full border border-outline-variant flex items-center justify-center hover:bg-surface-container-high transition-colors shrink-0">
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">visibility</span>
            </a>
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center py-24">
    <span class="material-symbols-outlined text-6xl text-outline-variant mb-4 block">flutter_dash</span>
    <h3 class="text-xl font-bold mb-2 text-on-surface">No species found</h3>
    <p class="text-on-surface-variant text-sm">Try adjusting your filters or search term.</p>
</div>
@endforelse

{{-- Pagination --}}
@if($birds->hasPages())
<div id="shop-pagination" class="col-span-full mt-10 flex justify-center">
    {{ $birds->links() }}
</div>
@endif
