@extends('layouts.admin')

@section('title', 'Add Bird | BirdHaven Admin')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.birds.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">Add New Bird</h2>
            <p class="text-on-surface-variant mt-1">Add a new bird to the inventory.</p>
        </div>
    </div>

    <form action="{{ route('admin.birds.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Image + Toggles --}}
            <div class="space-y-6">

                {{-- Image Upload --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <h3 class="text-sm font-bold text-on-surface mb-4">Bird Photo</h3>
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative">
                            <img id="image-preview"
                                 src="https://ui-avatars.com/api/?name=New+Bird&background=baeaff&color=005870&bold=true&size=256"
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

                {{-- Gallery --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <h3 class="text-sm font-bold text-on-surface mb-4">Gallery Images</h3>

                    <div id="gallery-new-preview" class="grid grid-cols-3 gap-2 mb-3 empty:hidden"></div>

                    <label for="gallery-input"
                           class="flex items-center justify-center gap-2 w-full py-3 border-2 border-dashed border-outline-variant/40 rounded-xl text-xs font-semibold text-on-surface-variant hover:border-primary hover:text-primary cursor-pointer transition-colors">
                        <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                        Add Images
                    </label>
                    <input id="gallery-input" name="gallery[]" type="file" accept="image/*" multiple class="hidden">
                    <p class="text-[10px] text-on-surface-variant mt-2 text-center">JPG, PNG or WebP · max 2 MB each</p>
                </div>

                {{-- Status Toggles --}}
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
                            <input type="hidden" name="featured" value="0">
                            <input type="checkbox" name="featured" value="1" id="featured"
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
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Bird Name *</label>
                            <input name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Hyacinth Macaw"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">SKU</label>
                            <input name="sku" type="text" value="{{ old('sku') }}" placeholder="BH-MAC-001"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm font-mono">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Category *</label>
                            <select name="category_id"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none appearance-none transition-all text-sm" required>
                                <option value="">Select category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Scientific Name</label>
                            <input name="species" type="text" value="{{ old('species') }}" placeholder="Anodorhynchus hyacinthinus"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm italic">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Badge Label</label>
                            <input name="badge" type="text" value="{{ old('badge') }}" placeholder="e.g. Rare, New, Sale"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>

                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Subtitle</label>
                            <input name="subtitle" type="text" value="{{ old('subtitle') }}" placeholder="Short tagline..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>

                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Description</label>
                            <textarea name="description" rows="4" placeholder="Detailed description..."
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
                            <input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Original Price</label>
                            <input name="original_price" type="number" step="0.01" min="0" value="{{ old('original_price') }}" placeholder="0.00"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Stock *</label>
                            <input name="stock" type="number" min="0" value="{{ old('stock', 0) }}"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" required>
                        </div>
                    </div>
                </div>

                {{-- Bird Details --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <h3 class="text-sm font-bold text-on-surface mb-4">Bird Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Age</label>
                            <input name="age" type="text" value="{{ old('age') }}" placeholder="e.g. 2 years"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Temperament</label>
                            <input name="temperament" type="text" value="{{ old('temperament') }}" placeholder="e.g. Playful, Social"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Color</label>
                            <input name="color" type="text" value="{{ old('color') }}" placeholder="e.g. Deep Blue"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Wingspan (cm)</label>
                            <input name="wingspan_cm" type="number" min="0" value="{{ old('wingspan_cm') }}"
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Habitat</label>
                            <textarea name="habitat" rows="2" placeholder="Natural habitat description..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('habitat') }}</textarea>
                        </div>
                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nutrition</label>
                            <textarea name="nutrition" rows="2" placeholder="Diet and feeding requirements..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('nutrition') }}</textarea>
                        </div>
                        <div class="col-span-2 space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Social Behaviour</label>
                            <textarea name="social" rows="2" placeholder="Interaction with other birds and humans..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('social') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Care Guide --}}
                <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-sky-50 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[18px]">menu_book</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface">Care Guide</h3>
                            <p class="text-[11px] text-on-surface-variant">Shown on the product page under "Mastering the Art of Care"</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-primary">home_health</span>
                                Habitat Design
                            </label>
                            <textarea name="habitat_guide" rows="3"
                                placeholder="e.g. Requires a spacious aviary with structural integrity. Hardwood perches of varying diameters are essential for beak maintenance..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('habitat_guide') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-secondary">restaurant</span>
                                Artisanal Nutrition
                            </label>
                            <textarea name="nutrition_guide" rows="3"
                                placeholder="e.g. A balanced diet of seeds, nuts, fresh fruits and leafy greens is essential. Avoid avocado, chocolate and caffeine..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('nutrition_guide') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">diversity_3</span>
                                Social Interaction
                            </label>
                            <textarea name="social_guide" rows="3"
                                placeholder="e.g. Highly intelligent and emotionally sensitive. Requires minimum 4 hours of daily interaction to prevent psychological distress..."
                                class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('social_guide') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-4 pt-2">
            <a href="{{ route('admin.birds.index') }}" class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
            <button type="submit"
                class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Add to Inventory
            </button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    // Main image preview
    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('image-preview').src = e.target.result;
        reader.readAsDataURL(file);
    });

    // Gallery preview
    document.getElementById('gallery-input').addEventListener('change', function () {
        const preview = document.getElementById('gallery-new-preview');
        preview.innerHTML = '';

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.className = 'relative group';
                wrap.innerHTML = `
                    <img src="${e.target.result}" class="w-full aspect-square object-cover rounded-lg">
                    <button type="button"
                        class="remove-new absolute top-1 right-1 w-6 h-6 bg-error text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow">
                        <span class="material-symbols-outlined text-[14px]">close</span>
                    </button>`;
                preview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });

        preview.addEventListener('click', e => {
            const btn = e.target.closest('.remove-new');
            if (btn) btn.closest('.relative').remove();
        });
    });
</script>
@endpush
