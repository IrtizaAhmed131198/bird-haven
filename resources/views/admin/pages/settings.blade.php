@extends('layouts.admin')

@section('title', 'Settings | BirdHaven Admin')

@section('content')

<div class="mb-8">
    <h1 class="text-4xl font-extrabold text-on-surface font-headline tracking-tight mb-2">Appearance Settings</h1>
    <p class="text-on-surface-variant">Configure your brand identity and store preferences.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Left: Form Sections --}}
        <div class="lg:col-span-7 space-y-8">

            {{-- Brand Identity --}}
            <section class="bg-surface-container-low rounded-lg p-8 space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-primary">branding_watermark</span>
                    <h2 class="text-xl font-bold font-headline">Brand Identity</h2>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Website Title</label>
                    <input name="site_title" type="text"
                        value="{{ old('site_title', $settings['site_title'] ?? 'BirdHaven') }}"
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Tagline</label>
                    <input name="site_tagline" type="text"
                        value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                        placeholder="Ethical Avian Guardianship"
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Contact Email</label>
                    <input name="contact_email" type="email"
                        value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                        placeholder="hello@birdhaven.pk"
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Contact Phone</label>
                    <input name="contact_phone" type="text"
                        value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                        placeholder="+92 300 0000000"
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Address</label>
                    <input name="contact_address" type="text"
                        value="{{ old('contact_address', $settings['contact_address'] ?? '') }}"
                        placeholder="123 Street, City, Pakistan"
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface-variant">Weekday Hours</label>
                        <input name="hours_weekday" type="text"
                            value="{{ old('hours_weekday', $settings['hours_weekday'] ?? '9:00 AM – 6:00 PM') }}"
                            class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface-variant">Saturday Hours</label>
                        <input name="hours_saturday" type="text"
                            value="{{ old('hours_saturday', $settings['hours_saturday'] ?? '10:00 AM – 4:00 PM') }}"
                            class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-on-surface-variant">Sunday Hours</label>
                        <input name="hours_sunday" type="text"
                            value="{{ old('hours_sunday', $settings['hours_sunday'] ?? 'Closed') }}"
                            class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" />
                    </div>
                </div>
            </section>

            {{-- Social Media --}}
            <section class="bg-surface-container-low rounded-lg p-8 space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-primary">share</span>
                    <h2 class="text-xl font-bold font-headline">Social Media Links</h2>
                </div>
                @foreach(['facebook' => 'Facebook URL', 'instagram' => 'Instagram URL', 'twitter' => 'X (Twitter) URL'] as $key => $placeholder)
                <div class="flex items-center bg-white rounded-DEFAULT px-4 py-3 gap-3 border border-outline-variant/10">
                    <span class="material-symbols-outlined text-slate-400">link</span>
                    <input name="{{ $key }}" type="text"
                        value="{{ old($key, $settings[$key] ?? '') }}"
                        placeholder="{{ $placeholder }}"
                        class="flex-1 border-none p-0 focus:ring-0 text-sm outline-none bg-transparent" />
                </div>
                @endforeach
            </section>

            {{-- Hero Banner --}}
            <section class="bg-surface-container-low rounded-lg p-8 space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-primary">panorama</span>
                    <h2 class="text-xl font-bold font-headline">Hero Banner</h2>
                </div>

                {{-- Hero Image Upload --}}
                <div class="space-y-3">
                    <label class="text-sm font-semibold text-on-surface-variant">Banner Image</label>
                    <div class="flex items-start gap-4">
                        <img id="hero-preview"
                             src="{{ !empty($settings['hero_image'] ?? null) ? asset('uploads/images/hero/' . $settings['hero_image']) : asset('assets/images/banner.png') }}"
                             onerror="this.src='{{ asset('assets/images/banner.png') }}'"
                             class="w-40 h-24 rounded-lg object-cover ring-2 ring-outline-variant/20 shrink-0">
                        <div class="flex-1 space-y-2">
                            <label for="hero_image_file"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg cursor-pointer hover:opacity-90 transition-opacity">
                                <span class="material-symbols-outlined text-[18px]">upload</span>
                                Choose Image
                            </label>
                            <input id="hero_image_file" name="hero_image" type="file" accept="image/*" class="hidden">
                            <p class="text-xs text-on-surface-variant">JPG, PNG or WebP · max 2 MB · recommended 1440×800</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Hero Headline</label>
                    <input name="hero_title" type="text"
                        value="{{ old('hero_title', $settings['hero_title'] ?? 'Elevating the Art of Avian Companionship') }}"
                        placeholder="Your main headline..."
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                    <p class="text-xs text-on-surface-variant">Wrap a word in [highlight]...[/highlight] to colour it with the accent.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Hero Subtitle</label>
                    <textarea name="hero_subtitle" rows="3"
                        placeholder="Supporting text below the headline..."
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'A curated sanctuary for the world\'s most magnificent birds and their guardians. Ethical, sustainable, and devoted to flight.') }}</textarea>
                </div>
            </section>

            {{-- Footer --}}
            <section class="bg-surface-container-low rounded-lg p-8 space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-primary">vertical_align_bottom</span>
                    <h2 class="text-xl font-bold font-headline">Footer Text</h2>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Copyright Text</label>
                    <textarea name="footer_text" rows="2"
                        class="w-full bg-white rounded-DEFAULT px-4 py-3 border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all">{{ old('footer_text', $settings['footer_text'] ?? '© ' . date('Y') . ' BirdHaven. All Rights Reserved.') }}</textarea>
                </div>
            </section>
        </div>

        {{-- Right: Preview --}}
        <div class="lg:col-span-5">
            <div class="sticky top-24">
                <div class="bg-white rounded-lg shadow-2xl shadow-sky-900/10 border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Preview</span>
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400/20"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400/20"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400/20"></div>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-500 uppercase">Current Settings</h3>
                            @foreach(['site_title' => 'Site Title', 'site_tagline' => 'Tagline', 'contact_email' => 'Email'] as $key => $label)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-on-surface-variant">{{ $label }}</span>
                                <span class="font-semibold text-on-surface">{{ $settings[$key] ?? '—' }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-500 uppercase">Buttons</h3>
                            <div class="flex gap-3 flex-wrap">
                                <button type="button" class="bg-primary text-white px-5 py-2 rounded-DEFAULT font-bold text-sm">Primary</button>
                                <button type="button" class="bg-secondary-container text-on-secondary-container px-5 py-2 rounded-DEFAULT font-semibold text-sm">Secondary</button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-primary/5 p-5 border-t border-slate-100 text-center">
                        <p class="text-[10px] text-slate-400">{{ $settings['footer_text'] ?? '© ' . date('Y') . ' BirdHaven' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky Save Bar --}}
    <div class="fixed bottom-0 right-0 w-[calc(100%-260px)] bg-white/90 backdrop-blur-md border-t border-slate-100 py-4 px-8 flex justify-between items-center z-50">
        <div class="flex items-center gap-3">
            <div class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></div>
            <span class="text-xs font-medium text-slate-500">Unsaved changes in Settings</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.settings.index') }}" class="text-slate-500 hover:text-slate-900 font-semibold px-6 py-2 transition-colors">Discard</a>
            <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold px-8 py-3 rounded-lg shadow-xl shadow-primary/25 active:scale-95 transition-all">
                Save Changes
            </button>
        </div>
    </div>
</form>

<div class="pb-20"></div>

@endsection

@push('scripts')
<script>
    document.getElementById('hero_image_file').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('hero-preview').src = e.target.result;
        reader.readAsDataURL(file);
    });
</script>
@endpush
