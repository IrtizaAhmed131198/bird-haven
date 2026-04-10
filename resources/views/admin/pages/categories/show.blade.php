@extends('layouts.admin')

@section('title', $category->name . ' | BirdHaven Admin')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.categories.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <nav class="flex text-[10px] font-bold text-on-surface-variant uppercase tracking-widest gap-2 mb-1">
                    <a href="{{ route('admin.categories.index') }}" class="hover:text-primary transition-colors">Categories</a>
                    <span>/</span>
                    <span class="text-primary">{{ $category->name }}</span>
                </nav>
                <h2 class="font-headline font-extrabold text-3xl text-on-surface">{{ $category->name }}</h2>
            </div>
        </div>
        <a href="{{ route('admin.categories.edit', $category) }}"
           class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[18px]">edit</span>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Sidebar: image + meta --}}
        <div class="space-y-5">

            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
                <img src="{{ $category->image_url }}"
                     alt="{{ $category->name }}"
                     class="w-full h-48 object-cover"
                     onerror="this.src='{{ asset('assets/images/default.png') }}'">
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Slug</p>
                        <p class="text-sm font-mono text-primary">{{ $category->slug }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                            {{ $category->is_active ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-error-container text-on-error-container' }}">
                            <span class="material-symbols-outlined text-[12px]">{{ $category->is_active ? 'check_circle' : 'cancel' }}</span>
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Birds in Category</p>
                        <p class="text-2xl font-headline font-black text-primary">{{ $category->birds_count }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Created</p>
                        <p class="text-sm text-on-surface">{{ $category->created_at->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-6">

            @if($category->description)
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                <h3 class="text-sm font-bold text-on-surface mb-3">Description</h3>
                <p class="text-sm text-on-surface-variant leading-relaxed">{{ $category->description }}</p>
            </div>
            @endif

            {{-- Birds in this category --}}
            <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-bold text-on-surface">
                        Birds in this Category
                        <span class="ml-2 px-2 py-0.5 bg-primary-fixed text-on-primary-container rounded-full text-[11px] font-bold">{{ $category->birds_count }}</span>
                    </h3>
                    <a href="{{ route('admin.birds.index') }}"
                       class="text-xs text-primary font-semibold hover:underline">View all</a>
                </div>

                @forelse($category->birds as $bird)
                <div class="flex items-center gap-4 py-3 {{ !$loop->last ? 'border-b border-outline-variant/10' : '' }}">
                    <img src="{{ $bird->image_url }}" alt="{{ $bird->name }}"
                         class="w-12 h-12 rounded-lg object-cover ring-2 ring-primary-container shrink-0"
                         onerror="this.src='{{ asset('assets/images/default.png') }}'">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-on-surface truncate">{{ $bird->name }}</p>
                        <p class="text-xs italic text-on-surface-variant">{{ $bird->species ?? '—' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-primary">${{ number_format($bird->price, 2) }}</p>
                        <p class="text-[11px] {{ $bird->stock === 0 ? 'text-error' : ($bird->stock === 1 ? 'text-secondary' : 'text-tertiary') }}">
                            {{ $bird->stock === 0 ? 'Out of stock' : ($bird->stock === 1 ? 'Low stock' : $bird->stock . ' in stock') }}
                        </p>
                    </div>
                    <a href="{{ route('admin.birds.edit', $bird) }}"
                       class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed/20 rounded-full transition-all shrink-0">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant text-center py-6">No birds assigned to this category yet.</p>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Delete --}}
    <div class="flex justify-end mt-6">
        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
              onsubmit="return confirm('Delete {{ $category->name }}? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" {{ $category->birds_count > 0 ? 'disabled' : '' }}
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95
                       {{ $category->birds_count > 0
                           ? 'bg-surface-container text-on-surface-variant cursor-not-allowed opacity-50'
                           : 'bg-error-container text-on-error-container hover:bg-error hover:text-white' }}">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                {{ $category->birds_count > 0 ? 'Cannot delete (has birds)' : 'Delete Category' }}
            </button>
        </form>
    </div>

</div>

@endsection
