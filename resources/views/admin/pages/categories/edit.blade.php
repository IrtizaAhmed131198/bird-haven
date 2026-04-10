@extends('layouts.admin')

@section('title', 'Edit ' . $category->name . ' | BirdHaven Admin')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">Edit Category</h2>
            <p class="text-on-surface-variant mt-1">Editing: <span class="font-semibold text-primary">{{ $category->name }}</span></p>
        </div>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Image --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4">Category Image</h3>
            <div class="flex items-center gap-6">
                <div class="relative shrink-0">
                    <img id="image-preview"
                         src="{{ $category->image_url }}"
                         alt="{{ $category->name }}"
                         class="h-28 w-28 rounded-xl object-cover ring-4 ring-primary-container">
                    <label for="image"
                           class="absolute bottom-0 right-0 h-9 w-9 bg-primary text-white rounded-full flex items-center justify-center cursor-pointer shadow-md hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </label>
                    <input id="image" name="image" type="file" accept="image/*" class="hidden">
                </div>
                <div>
                    <p class="text-sm font-medium text-on-surface">Change cover image</p>
                    <p class="text-xs text-on-surface-variant mt-1">JPG, PNG or WebP · max 2 MB</p>
                    <p class="text-xs text-on-surface-variant">Leave blank to keep current image.</p>
                </div>
            </div>
        </div>

        {{-- Core fields --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6 space-y-5">
            <h3 class="text-sm font-bold text-on-surface">Details</h3>

            <div class="space-y-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Name *</label>
                <input name="name" type="text" value="{{ old('name', $category->name) }}"
                    class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                <p class="text-xs text-on-surface-variant">
                    Current slug: <span class="font-mono text-primary">{{ $category->slug }}</span>
                    — will update if name changes.
                </p>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Description</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('description', $category->description) }}</textarea>
            </div>

            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-sm font-semibold text-on-surface">Active</p>
                    <p class="text-xs text-on-surface-variant">Show this category on the store</p>
                </div>
                <div class="relative">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $category->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:bg-primary
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                after:bg-white after:rounded-full after:h-5 after:w-5
                                after:transition-all peer-checked:after:translate-x-5"></div>
                </div>
            </label>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
            <button type="submit"
                class="px-8 py-3 bg-primary text-white font-bold rounded-DEFAULT shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Save Changes
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('image-preview').src = e.target.result;
        reader.readAsDataURL(file);
    });
</script>
@endpush
