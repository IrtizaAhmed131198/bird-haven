@extends('layouts.app')

@section('title', 'Avian Collection | Bird Haven')

@php
$colorMeta = [
    'red'    => ['label' => 'Crimson',  'hex' => '#ef4444'],
    'green'  => ['label' => 'Verdant',  'hex' => '#22c55e'],
    'blue'   => ['label' => 'Sky Blue', 'hex' => '#3b82f6'],
    'yellow' => ['label' => 'Golden',   'hex' => '#eab308'],
    'gray'   => ['label' => 'Slate',    'hex' => '#9ca3af'],
    'orange' => ['label' => 'Amber',    'hex' => '#f97316'],
    'brown'  => ['label' => 'Chestnut', 'hex' => '#92400e'],
    'white'  => ['label' => 'Pearl',    'hex' => '#e5e7eb'],
    'black'  => ['label' => 'Onyx',     'hex' => '#1f2937'],
];
@endphp

@push('styles')
<style>
    /* ── List view layout ─────────────────────────────────── */
    #birds-grid.list-view {
        display: flex !important;
        flex-direction: column;
        gap: 1rem;
    }
    #birds-grid.list-view .bird-card {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
        background: var(--md-sys-color-surface-container-lowest, #fff);
        border-radius: 0.75rem;
        padding: 1rem;
        box-shadow: 0 1px 6px rgba(0,0,0,.06);
    }
    #birds-grid.list-view .card-image {
        width: 160px;
        flex-shrink: 0;
        aspect-ratio: 1 / 1 !important;
        margin-bottom: 0 !important;
    }
    #birds-grid.list-view .card-body {
        flex: 1;
        padding-top: .25rem;
    }
    #birds-grid.list-view .list-only {
        display: flex !important;
    }

    /* ── Loading overlay ──────────────────────────────────── */
    #shop-results.loading::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,.6);
        backdrop-filter: blur(2px);
        border-radius: .75rem;
        z-index: 10;
    }

    /* ── Price range inputs ──────────────────────────────── */
    .price-input::-webkit-inner-spin-button,
    .price-input::-webkit-outer-spin-button { opacity: 1; }

    /* ── Active filter chips ─────────────────────────────── */
    .filter-chip-active { background: rgb(var(--md-ref-palette-primary40) / .12); color: #0c6780; border-color: rgba(12,103,128,.3); }
    .filter-chip { transition: all .15s; }
</style>
@endpush

@section('content')

{{-- ===== DESKTOP ===== --}}
<main class="hidden md:block pt-32 pb-24 px-12 max-w-[1440px] mx-auto min-h-screen">

    {{-- Header --}}
    <div class="flex items-end justify-between mb-10">
        <div>
            <span class="text-sm font-semibold tracking-[0.2em] text-primary uppercase mb-2 block">Curation</span>
            <h1 class="text-5xl font-headline font-extrabold text-on-surface tracking-tight leading-[1.1]">Avian Collection</h1>
        </div>
        <p class="max-w-sm text-on-surface-variant text-base leading-relaxed">
            Ethically raised companions, selected for health, temperament, and breathtaking plumage.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-8">

        {{-- ── Sidebar ──────────────────────────────────────────── --}}
        <aside class="col-span-3">
            <div class="bg-surface-container-low rounded-xl p-6 space-y-7 sticky top-32 shadow-sm">

                {{-- Search --}}
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[18px] text-on-surface-variant">search</span>
                    <input id="filter-search" type="text"
                           value="{{ request('search') }}"
                           placeholder="Search species…"
                           class="w-full pl-9 pr-4 py-2.5 bg-white rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all">
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="font-headline font-bold text-on-surface mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">filter_list</span>Species
                    </h3>
                    <div class="space-y-2.5">
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="filter-category w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20"
                                   value="{{ $cat->id }}"
                                   {{ in_array($cat->id, (array) request('category')) ? 'checked' : '' }}>
                            <span class="text-sm text-on-surface-variant group-hover:text-primary transition-colors">{{ $cat->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Color --}}
                @if($colors->isNotEmpty())
                <div>
                    <h3 class="font-headline font-bold text-on-surface mb-4 text-sm uppercase tracking-wider">Colour</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($colors as $color)
                        @php $meta = $colorMeta[$color] ?? ['label' => ucfirst($color), 'hex' => '#888']; @endphp
                        <button type="button"
                                class="filter-color filter-chip flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border border-outline-variant/30 bg-white hover:border-primary hover:text-primary"
                                data-color="{{ $color }}"
                                {{ in_array($color, (array) request('color')) ? 'data-active=true' : '' }}>
                            <span class="w-3 h-3 rounded-full border border-black/10 shrink-0"
                                  style="background:{{ $meta['hex'] }}"></span>
                            {{ $meta['label'] }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Price Range --}}
                <div>
                    <h3 class="font-headline font-bold text-on-surface mb-4 text-sm uppercase tracking-wider">Price Range</h3>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant">$</span>
                            <input id="filter-price-min" type="number" min="0"
                                   value="{{ request('price_min', $priceRange['min']) }}"
                                   placeholder="{{ $priceRange['min'] }}"
                                   class="price-input w-full pl-5 pr-2 py-2 bg-white rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-xs">
                        </div>
                        <span class="text-on-surface-variant text-xs">–</span>
                        <div class="relative flex-1">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-on-surface-variant">$</span>
                            <input id="filter-price-max" type="number" min="0"
                                   value="{{ request('price_max', $priceRange['max']) }}"
                                   placeholder="{{ $priceRange['max'] }}"
                                   class="price-input w-full pl-5 pr-2 py-2 bg-white rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-xs">
                        </div>
                    </div>
                </div>

                {{-- Wingspan --}}
                <div>
                    <h3 class="font-headline font-bold text-on-surface mb-4 text-sm uppercase tracking-wider">Wingspan</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['compact' => 'Compact (<30cm)', 'moderate' => 'Moderate', 'grand' => 'Grand', 'majestic' => 'Majestic (>1m)'] as $val => $label)
                        <button type="button"
                                class="filter-wingspan filter-chip py-2 rounded-lg border text-xs font-medium transition-all border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary"
                                data-wingspan="{{ $val }}"
                                {{ request('wingspan') === $val ? 'data-active=true' : '' }}>
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Reset --}}
                <button id="reset-filters"
                        class="w-full py-2 rounded-lg border border-outline-variant/30 text-xs font-semibold text-on-surface-variant hover:border-error hover:text-error transition-colors">
                    Clear All Filters
                </button>

            </div>
        </aside>

        {{-- ── Main Grid ─────────────────────────────────────────── --}}
        <section class="col-span-9">

            {{-- Top Bar --}}
            <div class="flex items-center justify-between mb-6 bg-surface-container-low/60 px-5 py-3.5 rounded-xl">
                <p id="results-count" class="text-sm text-on-surface-variant">
                    Showing <span class="font-bold text-on-surface">{{ $birds->firstItem() ?? 0 }}–{{ $birds->lastItem() ?? 0 }}</span>
                    of <span class="font-bold text-on-surface">{{ $birds->total() }}</span> residents
                </p>
                <div class="flex items-center gap-5">
                    {{-- Sort --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase tracking-widest font-bold text-on-surface-variant">Sort:</span>
                        <select id="filter-sort"
                                class="bg-transparent border-none text-sm font-bold text-primary focus:ring-0 cursor-pointer outline-none">
                            <option value="featured"    {{ request('sort','featured') === 'featured'   ? 'selected' : '' }}>Featured</option>
                            <option value="newest"      {{ request('sort') === 'newest'     ? 'selected' : '' }}>Newest</option>
                            <option value="price_low"   {{ request('sort') === 'price_low'  ? 'selected' : '' }}>Price: Low → High</option>
                            <option value="price_high"  {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High → Low</option>
                        </select>
                    </div>
                    {{-- View Toggle --}}
                    <div class="flex gap-1">
                        <button id="btn-grid" title="Grid view"
                                class="p-2 rounded-md bg-white shadow-sm text-primary transition-all">
                            <span class="material-symbols-outlined text-[20px]">grid_view</span>
                        </button>
                        <button id="btn-list" title="List view"
                                class="p-2 rounded-md text-outline hover:bg-surface-container-high transition-all">
                            <span class="material-symbols-outlined text-[20px]">view_list</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Grid Container --}}
            <div id="shop-results" class="relative">
                <div id="birds-grid" class="grid grid-cols-3 gap-5">
                    @include('pages._shop_grid', ['birds' => $birds])
                </div>
            </div>

        </section>
    </div>
</main>

{{-- ===== MOBILE ===== --}}
<div class="md:hidden pt-20 pb-28 px-4 min-h-screen">

    <div class="mb-6 pt-4 space-y-1">
        <span class="text-xs font-bold uppercase tracking-widest text-primary">Curated Collection</span>
        <h1 class="text-3xl font-extrabold tracking-tight text-on-surface font-headline">Avian Collection</h1>
    </div>

    {{-- Mobile Search --}}
    <div class="relative mb-4">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[18px] text-on-surface-variant">search</span>
        <input id="mobile-search" type="text" placeholder="Search species…"
               class="w-full pl-9 pr-4 py-3 bg-surface-container-low rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary/20">
    </div>

    {{-- Mobile Category Chips --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1 no-scrollbar">
        <button class="mobile-cat filter-chip shrink-0 px-4 py-2 rounded-full text-xs font-semibold bg-secondary-container text-on-secondary-container filter-chip-active"
                data-cat="">All</button>
        @foreach($categories as $cat)
        <button class="mobile-cat filter-chip shrink-0 px-4 py-2 rounded-full text-xs font-semibold bg-surface-container-low text-on-surface-variant border border-outline-variant/20"
                data-cat="{{ $cat->id }}">{{ $cat->name }}</button>
        @endforeach
    </div>

    {{-- Mobile Sort --}}
    <div class="flex items-center justify-between mb-5">
        <p id="mobile-count" class="text-xs text-on-surface-variant font-medium">
            <span class="font-bold text-on-surface">{{ $birds->total() }}</span> residents
        </p>
        <div class="flex items-center gap-1 text-primary">
            <span class="material-symbols-outlined text-[18px]">swap_vert</span>
            <select id="mobile-sort" class="bg-transparent border-none text-xs font-bold text-primary focus:ring-0 outline-none cursor-pointer">
                <option value="featured">Featured</option>
                <option value="newest">Newest</option>
                <option value="price_low">Price: Low → High</option>
                <option value="price_high">Price: High → Low</option>
            </select>
        </div>
    </div>

    {{-- Mobile Results --}}
    <div id="mobile-results" class="space-y-4">
        @forelse($birds as $bird)
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm flex gap-0">
            <div class="w-28 shrink-0">
                <img src="{{ $bird->image_url }}" alt="{{ $bird->name }}"
                     class="w-full h-full object-cover"
                     loading="lazy"
                     onerror="this.src='{{ asset('assets/images/default.png') }}'">
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    @if($bird->badge)
                        <span class="text-[9px] font-bold uppercase tracking-widest text-primary">{{ $bird->badge }}</span>
                    @endif
                    <h3 class="text-sm font-bold text-on-surface leading-tight">{{ $bird->name }}</h3>
                    <p class="text-[11px] text-on-surface-variant line-clamp-1 mt-0.5">{{ $bird->subtitle }}</p>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-sm font-bold text-primary">${{ number_format($bird->price) }}</span>
                    <a href="{{ route('bird.show', $bird->slug) }}"
                       class="text-[11px] font-semibold text-primary border border-primary/30 px-3 py-1 rounded-full">View</a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <span class="material-symbols-outlined text-5xl text-outline-variant mb-3 block">flutter_dash</span>
            <p class="text-on-surface-variant text-sm">No species found.</p>
        </div>
        @endforelse
    </div>

    {{-- Mobile Pagination --}}
    @if($birds->hasPages())
    <div class="mt-8 flex justify-center">{{ $birds->links() }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script>
(function () {
    // ── State ───────────────────────────────────────────────────────────────
    const state = {
        search   : '{{ request("search", "") }}',
        category : {!! json_encode((array) request('category', [])) !!},
        color    : {!! json_encode((array) request('color', [])) !!},
        wingspan : '{{ request("wingspan", "") }}',
        price_min: '{{ request("price_min", "") }}',
        price_max: '{{ request("price_max", "") }}',
        sort     : '{{ request("sort", "featured") }}',
        page     : 1,
    };

    let viewMode    = 'grid';
    let fetchTimer  = null;

    // ── Elements ────────────────────────────────────────────────────────────
    const grid        = document.getElementById('birds-grid');
    const results     = document.getElementById('shop-results');
    const countEl     = document.getElementById('results-count');
    const btnGrid     = document.getElementById('btn-grid');
    const btnList     = document.getElementById('btn-list');

    // ── Fetch ────────────────────────────────────────────────────────────────
    function loadResults(resetPage = true) {
        if (resetPage) state.page = 1;

        const params = new URLSearchParams();
        if (state.search)    params.set('search',    state.search);
        if (state.sort)      params.set('sort',      state.sort);
        if (state.wingspan)  params.set('wingspan',  state.wingspan);
        if (state.price_min) params.set('price_min', state.price_min);
        if (state.price_max) params.set('price_max', state.price_max);
        if (state.page > 1)  params.set('page',      state.page);
        state.category.forEach(c => params.append('category[]', c));
        state.color.forEach(c    => params.append('color[]', c));

        // Update browser URL without reload
        const newUrl = '{{ route('shop') }}' + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);

        results.classList.add('loading');

        fetch('{{ route('shop') }}?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            grid.innerHTML = html;
            results.classList.remove('loading');
            applyViewMode();
            updateCount();
            bindPaginationLinks();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(() => results.classList.remove('loading'));
    }

    function debounced(fn, ms = 350) {
        clearTimeout(fetchTimer);
        fetchTimer = setTimeout(fn, ms);
    }

    // ── Count display ────────────────────────────────────────────────────────
    function updateCount() {
        const meta = document.getElementById('grid-meta');
        if (!meta || !countEl) return;
        const total = meta.dataset.total;
        const from  = meta.dataset.from;
        const to    = meta.dataset.to;
        countEl.innerHTML = `Showing <span class="font-bold text-on-surface">${from}–${to}</span> of <span class="font-bold text-on-surface">${total}</span> residents`;
    }

    // ── View mode ────────────────────────────────────────────────────────────
    function applyViewMode() {
        if (viewMode === 'list') {
            grid.classList.remove('grid-cols-3');
            grid.classList.add('list-view');
            btnList.classList.add('bg-white', 'shadow-sm', 'text-primary');
            btnList.classList.remove('text-outline');
            btnGrid.classList.remove('bg-white', 'shadow-sm', 'text-primary');
            btnGrid.classList.add('text-outline');
        } else {
            grid.classList.add('grid-cols-3');
            grid.classList.remove('list-view');
            btnGrid.classList.add('bg-white', 'shadow-sm', 'text-primary');
            btnGrid.classList.remove('text-outline');
            btnList.classList.remove('bg-white', 'shadow-sm', 'text-primary');
            btnList.classList.add('text-outline');
        }
    }

    btnGrid.addEventListener('click', () => { viewMode = 'grid'; applyViewMode(); });
    btnList.addEventListener('click', () => { viewMode = 'list'; applyViewMode(); });

    // ── Pagination ───────────────────────────────────────────────────────────
    function bindPaginationLinks() {
        document.querySelectorAll('#shop-pagination a[href]').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const url    = new URL(link.href);
                state.page   = url.searchParams.get('page') || 1;
                loadResults(false);
            });
        });
    }

    // ── Filter: search ───────────────────────────────────────────────────────
    document.getElementById('filter-search').addEventListener('input', function () {
        state.search = this.value;
        debounced(() => loadResults());
    });

    // ── Filter: sort ─────────────────────────────────────────────────────────
    document.getElementById('filter-sort').addEventListener('change', function () {
        state.sort = this.value;
        loadResults();
    });

    // ── Filter: categories ───────────────────────────────────────────────────
    document.querySelectorAll('.filter-category').forEach(cb => {
        cb.addEventListener('change', function () {
            const id = this.value;
            if (this.checked) {
                if (!state.category.includes(id)) state.category.push(id);
            } else {
                state.category = state.category.filter(c => c !== id);
            }
            loadResults();
        });
    });

    // ── Filter: color chips ──────────────────────────────────────────────────
    document.querySelectorAll('.filter-color').forEach(btn => {
        // Apply initial active state
        if (state.color.includes(btn.dataset.color)) {
            btn.classList.add('filter-chip-active');
        }
        btn.addEventListener('click', function () {
            const c = this.dataset.color;
            if (state.color.includes(c)) {
                state.color = state.color.filter(x => x !== c);
                this.classList.remove('filter-chip-active');
            } else {
                state.color.push(c);
                this.classList.add('filter-chip-active');
            }
            loadResults();
        });
    });

    // ── Filter: wingspan ─────────────────────────────────────────────────────
    document.querySelectorAll('.filter-wingspan').forEach(btn => {
        if (state.wingspan === btn.dataset.wingspan) btn.classList.add('filter-chip-active');
        btn.addEventListener('click', function () {
            const w = this.dataset.wingspan;
            if (state.wingspan === w) {
                state.wingspan = '';
                this.classList.remove('filter-chip-active');
            } else {
                document.querySelectorAll('.filter-wingspan').forEach(b => b.classList.remove('filter-chip-active'));
                state.wingspan = w;
                this.classList.add('filter-chip-active');
            }
            loadResults();
        });
    });

    // ── Filter: price ────────────────────────────────────────────────────────
    ['filter-price-min', 'filter-price-max'].forEach(id => {
        document.getElementById(id).addEventListener('input', function () {
            if (id === 'filter-price-min') state.price_min = this.value;
            else state.price_max = this.value;
            debounced(() => loadResults(), 500);
        });
    });

    // ── Reset all filters ────────────────────────────────────────────────────
    document.getElementById('reset-filters').addEventListener('click', () => {
        state.search   = '';
        state.category = [];
        state.color    = [];
        state.wingspan = '';
        state.price_min = '';
        state.price_max = '';
        state.sort     = 'featured';
        state.page     = 1;

        document.getElementById('filter-search').value = '';
        document.getElementById('filter-sort').value   = 'featured';
        document.querySelectorAll('.filter-category').forEach(c => c.checked = false);
        document.querySelectorAll('.filter-color, .filter-wingspan').forEach(b => b.classList.remove('filter-chip-active'));

        const minInput = document.getElementById('filter-price-min');
        const maxInput = document.getElementById('filter-price-max');
        if (minInput) minInput.value = minInput.placeholder;
        if (maxInput) maxInput.value = maxInput.placeholder;

        loadResults();
    });

    // ── Mobile: category chips ───────────────────────────────────────────────
    document.querySelectorAll('.mobile-cat').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.mobile-cat').forEach(b => b.classList.remove('filter-chip-active', 'bg-secondary-container', 'text-on-secondary-container'));
            this.classList.add('filter-chip-active', 'bg-secondary-container', 'text-on-secondary-container');
            state.category = this.dataset.cat ? [this.dataset.cat] : [];
            loadResults();
        });
    });

    // ── Mobile: search ───────────────────────────────────────────────────────
    const mobileSearch = document.getElementById('mobile-search');
    if (mobileSearch) {
        mobileSearch.addEventListener('input', function () {
            state.search = this.value;
            debounced(() => loadResults());
        });
    }

    // ── Mobile: sort ─────────────────────────────────────────────────────────
    const mobileSort = document.getElementById('mobile-sort');
    if (mobileSort) {
        mobileSort.addEventListener('change', function () {
            state.sort = this.value;
            loadResults();
        });
    }

    // ── Init ─────────────────────────────────────────────────────────────────
    bindPaginationLinks();
    applyViewMode();
})();
</script>
@endpush
