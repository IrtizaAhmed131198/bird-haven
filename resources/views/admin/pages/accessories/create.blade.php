@extends('layouts.admin')

@section('title', 'Add Accessory | BirdHaven Admin')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.accessories.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">Add New Accessory</h2>
            <p class="text-on-surface-variant mt-1">Add a new product to the accessories catalogue.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-xl text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.accessories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Image + Toggles --}}
            <div class="space-y-6">

                {{-- Image Upload --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <h3 class="text-sm font-bold text-on-surface mb-4">Product Photo</h3>
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative">
                            <img id="image-preview"
                                 src="{{ asset('assets/images/default.png') }}"
                                 alt="Preview"
                                 class="h-36 w-36 rounded-xl object-cover ring-4 ring-primary-container">
                            <label for="image"
                                   class="absolute bottom-0 right-0 h-9 w-9 bg-primary text-white rounded-full flex items-center justify-center cursor-pointer shadow-md hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                            </label>
                        </div>
                        <input id="image" name="image" type="file" accept="image/*" class="hidden">
                        <p class="text-xs text-on-surface-variant text-center">JPG, PNG or WebP · max 2 MB</p>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6 space-y-4">
                    <h3 class="text-sm font-bold text-on-surface mb-2">Settings</h3>

                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <p class="text-sm font-semibold text-on-surface">Visible on Store</p>
                            <p class="text-xs text-on-surface-variant">Show to customers</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" id="is_active" checked
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:bg-primary
                                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                        after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all peer-checked:after:translate-x-5"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <p class="text-sm font-semibold text-on-surface">Featured</p>
                            <p class="text-xs text-on-surface-variant">Show on homepage</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" id="is_featured"
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:bg-primary
                                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                        after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all peer-checked:after:translate-x-5"></div>
                        </div>
                    </label>
                </div>

            </div>

            {{-- Right: Main Fields --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Core Info --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <h3 class="text-sm font-bold text-on-surface mb-4">Core Information</h3>
                    <div class="grid grid-cols-2 gap-4">

                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Name *</label>
                            <input name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Skyline Habitat Cage"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                        </div>

                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Type *</label>
                            <select name="type"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none appearance-none transition-all text-sm" required>
                                <option value="">Select type</option>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Description</label>
                            <textarea name="description" rows="4" placeholder="Describe the product..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Pricing & Stock --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <h3 class="text-sm font-bold text-on-surface mb-4">Pricing &amp; Stock</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">$</span>
                                <input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00"
                                    class="w-full pl-7 pr-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Original Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">$</span>
                                <input name="original_price" type="number" step="0.01" min="0" value="{{ old('original_price') }}" placeholder="0.00"
                                    class="w-full pl-7 pr-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Stock *</label>
                            <input name="stock" type="number" min="0" value="{{ old('stock', 0) }}"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 pt-2">
            <a href="{{ route('admin.accessories.index') }}" class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
            <button type="submit"
                class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Add Accessory
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
