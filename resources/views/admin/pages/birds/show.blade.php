@extends('layouts.admin')

@section('title', $bird->name . ' | BirdHaven Admin')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.birds.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <nav class="flex text-[10px] font-bold text-on-surface-variant uppercase tracking-widest gap-2 mb-1">
                    <a href="{{ route('admin.birds.index') }}" class="hover:text-primary transition-colors">Inventory</a>
                    <span>/</span>
                    <span class="text-primary">{{ $bird->name }}</span>
                </nav>
                <h2 class="font-headline font-extrabold text-3xl text-on-surface">{{ $bird->name }}</h2>
            </div>
        </div>
        <a href="{{ route('admin.birds.edit', $bird) }}"
           class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Edit Bird
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Image & Quick Stats --}}
        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
                <img src="{{ $bird->image ? asset('uploads/images/birds/' . $bird->image) : 'https://ui-avatars.com/api/?name=' . urlencode($bird->name) . '&background=baeaff&color=005870&bold=true&size=512' }}"
                     alt="{{ $bird->name }}"
                     class="w-full h-56 object-cover">
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-headline font-black text-primary">${{ number_format($bird->price, 2) }}</span>
                        @if($bird->original_price)
                            <span class="text-sm text-on-surface-variant line-through">${{ number_format($bird->original_price, 2) }}</span>
                        @endif
                    </div>
                    @if($bird->sku)
                    <div class="text-xs font-mono bg-surface-container text-on-surface-variant px-3 py-1.5 rounded-lg inline-block">
                        {{ $bird->sku }}
                    </div>
                    @endif

                    {{-- Stock badge --}}
                    @php
                        $stockLevel = $bird->stock === 0 ? 'out' : ($bird->stock === 1 ? 'low' : 'in');
                        $stockLabel = match($stockLevel) {
                            'in'  => 'In Stock (' . $bird->stock . ' units)',
                            'low' => 'Low Stock (1 unit)',
                            'out' => 'Out of Stock',
                        };
                        $stockClass = match($stockLevel) {
                            'in'  => 'bg-tertiary-container text-on-tertiary-container',
                            'low' => 'bg-secondary-container text-on-secondary-container',
                            'out' => 'bg-error-container text-on-error-container',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold {{ $stockClass }}">
                        <span class="w-2 h-2 rounded-full {{ $stockLevel === 'in' ? 'bg-tertiary' : ($stockLevel === 'low' ? 'bg-secondary' : 'bg-error') }}"></span>
                        {{ $stockLabel }}
                    </span>

                    <div class="flex gap-2 pt-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold
                            {{ $bird->is_active ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-error-container text-on-error-container' }}">
                            <span class="material-symbols-outlined text-[12px]">{{ $bird->is_active ? 'visibility' : 'visibility_off' }}</span>
                            {{ $bird->is_active ? 'Visible' : 'Hidden' }}
                        </span>
                        @if($bird->featured)
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

            {{-- Core Info --}}
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                <h3 class="text-sm font-bold text-on-surface mb-4">Core Information</h3>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Category</dt>
                        <dd class="text-on-surface font-medium">{{ $bird->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Scientific Name</dt>
                        <dd class="text-on-surface italic">{{ $bird->species ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Badge</dt>
                        <dd>{!! $bird->badge ? '<span class="px-2 py-0.5 bg-primary-fixed text-on-primary-container rounded-full text-xs font-bold">' . $bird->badge . '</span>' : '—' !!}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Slug</dt>
                        <dd class="text-on-surface-variant font-mono text-xs">{{ $bird->slug }}</dd>
                    </div>
                    @if($bird->subtitle)
                    <div class="col-span-2">
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Subtitle</dt>
                        <dd class="text-on-surface">{{ $bird->subtitle }}</dd>
                    </div>
                    @endif
                    @if($bird->description)
                    <div class="col-span-2">
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">Description</dt>
                        <dd class="text-on-surface leading-relaxed">{{ $bird->description }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Bird Details --}}
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                <h3 class="text-sm font-bold text-on-surface mb-4">Bird Details</h3>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    @foreach(['age' => 'Age', 'temperament' => 'Temperament', 'color' => 'Color', 'wingspan_cm' => 'Wingspan (cm)'] as $field => $label)
                    <div>
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">{{ $label }}</dt>
                        <dd class="text-on-surface">{{ $bird->$field ?? '—' }}</dd>
                    </div>
                    @endforeach
                    @foreach(['habitat' => 'Habitat', 'nutrition' => 'Nutrition', 'social' => 'Social Behaviour'] as $field => $label)
                    @if($bird->$field)
                    <div class="col-span-2">
                        <dt class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">{{ $label }}</dt>
                        <dd class="text-on-surface leading-relaxed">{{ $bird->$field }}</dd>
                    </div>
                    @endif
                    @endforeach
                </dl>
            </div>

            {{-- Reviews --}}
            @if($bird->reviews->isNotEmpty())
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                <h3 class="text-sm font-bold text-on-surface mb-4">Reviews ({{ $bird->reviews->count() }})</h3>
                <div class="space-y-3">
                    @foreach($bird->reviews->take(5) as $review)
                    <div class="bg-surface-container rounded-lg p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-semibold text-on-surface">{{ $review->user?->name ?? 'Anonymous' }}</span>
                            <span class="text-xs text-on-surface-variant">{{ $review->created_at->format('d M, Y') }}</span>
                        </div>
                        <p class="text-sm text-on-surface-variant">{{ $review->comment ?? $review->body ?? '' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Delete --}}
    <div class="flex justify-end mt-6">
        <form action="{{ route('admin.birds.destroy', $bird) }}" method="POST"
              onsubmit="return confirm('Permanently delete {{ $bird->name }}?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 bg-error-container text-on-error-container rounded-xl font-bold text-sm hover:bg-error hover:text-white transition-all active:scale-95">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                Delete Bird
            </button>
        </form>
    </div>

</div>

@endsection
