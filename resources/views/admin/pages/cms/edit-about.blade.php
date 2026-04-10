@extends('layouts.admin')

@section('title', 'Edit About Page | BirdHaven Admin')

@section('content')

@php $m = $page->meta ?? []; @endphp

<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cms.pages.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <nav class="flex text-[10px] font-bold text-on-surface-variant uppercase tracking-widest gap-2 mb-1">
                    <a href="{{ route('admin.cms.pages.index') }}" class="hover:text-primary transition-colors">CMS Pages</a>
                    <span>/</span>
                    <span class="text-primary">About</span>
                </nav>
                <h2 class="font-headline font-extrabold text-3xl text-on-surface">Edit About Page</h2>
            </div>
        </div>
        <a href="{{ route('about') }}" target="_blank"
           class="flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            Preview
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-tertiary-container text-on-tertiary-container rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-xl text-sm space-y-1">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form action="{{ route('admin.cms.pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- ── Page Settings ──────────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">settings</span>
                Page Settings
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Meta Description</label>
                    <input name="meta_description" type="text"
                        value="{{ old('meta_description', $page->meta_description) }}"
                        placeholder="Short SEO description (max 160 chars)"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <label class="flex items-center justify-between w-full cursor-pointer">
                        <div>
                            <p class="text-sm font-semibold text-on-surface">Published</p>
                            <p class="text-xs text-on-surface-variant">Visible on site</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" id="is_published"
                                {{ $page->is_published ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:bg-primary
                                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                        after:bg-white after:rounded-full after:h-5 after:w-5
                                        after:transition-all peer-checked:after:translate-x-5"></div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Hero ───────────────────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">title</span>
                Hero Section
            </h3>
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Desktop Headline</label>
                    <input name="hero_headline" type="text"
                        value="{{ old('hero_headline', $m['hero_headline'] ?? '') }}"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Desktop Intro</label>
                    <textarea name="hero_intro" rows="3"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('hero_intro', $m['hero_intro'] ?? '') }}</textarea>
                </div>
                <div class="border-t border-outline-variant/10 pt-4 grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Mobile Headline</label>
                        <input name="mobile_hero_headline" type="text"
                            value="{{ old('mobile_hero_headline', $m['mobile_hero_headline'] ?? '') }}"
                            class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Mobile Intro</label>
                        <textarea name="mobile_hero_intro" rows="3"
                            class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('mobile_hero_intro', $m['mobile_hero_intro'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Story ──────────────────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">auto_stories</span>
                Story Section
            </h3>
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Title</label>
                    <input name="story_title" type="text"
                        value="{{ old('story_title', $m['story_title'] ?? '') }}"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">First Paragraph</label>
                        <textarea name="story_body_1" rows="5"
                            class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('story_body_1', $m['story_body_1'] ?? '') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Second Paragraph</label>
                        <textarea name="story_body_2" rows="5"
                            class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('story_body_2', $m['story_body_2'] ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Banner images --}}
                <div class="grid grid-cols-2 gap-4 border-t border-outline-variant/10 pt-4">
                    @foreach([['key' => 'story_banner', 'label' => 'Main Banner Image', 'fallback' => 'about-banner.png'], ['key' => 'story_banner_child', 'label' => 'Inset Image', 'fallback' => 'about-banner-child.png']] as $img)
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">{{ $img['label'] }}</label>
                        <img id="preview-{{ $img['key'] }}"
                             src="{{ !empty($m[$img['key']]) ? asset('uploads/images/about/' . $m[$img['key']]) : asset('assets/images/' . $img['fallback']) }}"
                             onerror="this.src='{{ asset('assets/images/' . $img['fallback']) }}'"
                             class="w-full h-32 object-cover rounded-lg ring-2 ring-outline-variant/20">
                        <label for="{{ $img['key'] }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container text-on-surface text-xs font-semibold rounded-lg cursor-pointer hover:bg-surface-container-high transition-colors border border-outline-variant/20">
                            <span class="material-symbols-outlined text-[16px]">upload</span>
                            Change Image
                        </label>
                        <input id="{{ $img['key'] }}" name="{{ $img['key'] }}" type="file" accept="image/*" class="hidden"
                               data-preview="preview-{{ $img['key'] }}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Ethical Mandate ────────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">eco</span>
                Ethical Mandate
            </h3>
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Quote</label>
                    <textarea name="mandate_quote" rows="4"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('mandate_quote', $m['mandate_quote'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block mb-3">Stats (3)</label>
                    <div class="grid grid-cols-3 gap-4">
                        @for($i = 0; $i < 3; $i++)
                        @php $stat = $m['mandate_stats'][$i] ?? []; @endphp
                        <div class="bg-surface-container rounded-lg p-4 space-y-2">
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Stat {{ $i + 1 }}</p>
                            <input name="mandate_stats[{{ $i }}][value]" type="text"
                                value="{{ old("mandate_stats.{$i}.value", $stat['value'] ?? '') }}"
                                placeholder="e.g. 500+"
                                class="w-full px-3 py-2 bg-white rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm font-bold">
                            <input name="mandate_stats[{{ $i }}][label]" type="text"
                                value="{{ old("mandate_stats.{$i}.label", $stat['label'] ?? '') }}"
                                placeholder="e.g. Species Documented"
                                class="w-full px-3 py-2 bg-white rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-xs">
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Team ───────────────────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">group</span>
                Team Members (3)
            </h3>
            <div class="space-y-6">
                @for($i = 0; $i < 3; $i++)
                @php
                    $member   = $m['team'][$i] ?? [];
                    $hasImage = !empty($member['image']);
                    $imgSrc   = $hasImage
                        ? asset('uploads/images/team/' . $member['image'])
                        : asset('assets/images/team-' . ($i + 1) . '.png');
                @endphp
                <div class="border border-outline-variant/10 rounded-xl p-5">
                    <p class="text-xs font-bold text-primary uppercase tracking-wider mb-4">Member {{ $i + 1 }}</p>
                    <div class="flex gap-5">
                        {{-- Photo --}}
                        <div class="flex flex-col items-center gap-3 shrink-0">
                            <img id="preview-team-{{ $i }}"
                                 src="{{ $imgSrc }}"
                                 onerror="this.src='{{ asset('assets/images/default.png') }}'"
                                 class="w-24 h-24 rounded-xl object-cover ring-2 ring-primary-container">
                            <label for="team_image_{{ $i }}"
                                   class="text-[10px] font-semibold px-3 py-1.5 bg-surface-container rounded-lg cursor-pointer hover:bg-surface-container-high border border-outline-variant/20 transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">upload</span>
                                Photo
                            </label>
                            <input id="team_image_{{ $i }}" name="team[{{ $i }}][image]" type="file"
                                   accept="image/*" class="hidden" data-preview="preview-team-{{ $i }}">
                        </div>
                        {{-- Fields --}}
                        <div class="flex-1 grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Name</label>
                                <input name="team[{{ $i }}][name]" type="text"
                                    value="{{ old("team.{$i}.name", $member['name'] ?? '') }}"
                                    class="w-full px-3 py-2.5 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Role</label>
                                <input name="team[{{ $i }}][role]" type="text"
                                    value="{{ old("team.{$i}.role", $member['role'] ?? '') }}"
                                    class="w-full px-3 py-2.5 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Bio</label>
                                <textarea name="team[{{ $i }}][bio]" rows="3"
                                    class="w-full px-3 py-2.5 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm resize-none">{{ old("team.{$i}.bio", $member['bio'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ── CTA ─────────────────────────────────────────────────────────── --}}
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">campaign</span>
                Call to Action
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Title</label>
                    <input name="cta_title" type="text"
                        value="{{ old('cta_title', $m['cta_title'] ?? '') }}"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Subtitle</label>
                    <textarea name="cta_subtitle" rows="3"
                        class="w-full px-4 py-3 bg-surface-container rounded-lg ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm resize-none">{{ old('cta_subtitle', $m['cta_subtitle'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Save --}}
        <div class="flex justify-end gap-4 pt-2">
            <a href="{{ route('admin.cms.pages.index') }}" class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
            <button type="submit"
                class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Save About Page
            </button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    // Generic image preview for all file inputs with data-preview attribute
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => document.getElementById(this.dataset.preview).src = e.target.result;
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
