@extends('layouts.admin')

@section('title', 'Edit ' . $page->title . ' | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
<style>
    .note-editor.note-frame { border-radius: 0.75rem; border: none; box-shadow: 0 0 0 1px rgba(191,200,205,.2); overflow: hidden; }
    .note-editor.note-frame.focus { box-shadow: 0 0 0 2px rgba(12,103,128,.2); }
    .note-toolbar { background: #f2f4f5 !important; border-bottom: 1px solid rgba(191,200,205,.2) !important; padding: 6px 8px !important; }
    .note-btn { border-radius: 0.5rem !important; font-size: 0.8rem !important; }
    .note-editable { background: #fff; font-family: 'Inter', sans-serif; font-size: 0.9rem; line-height: 1.75; min-height: 320px; padding: 1.25rem 1.5rem !important; }
    .note-statusbar { background: #f2f4f5 !important; border-top: 1px solid rgba(191,200,205,.15) !important; }
    .note-resizebar { height: 6px !important; }
</style>
@endpush

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.cms.pages.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">Edit Page</h2>
            <p class="text-on-surface-variant mt-1">{{ $page->title }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            @foreach ($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-8">
        <form action="{{ route('admin.cms.pages.update', $page) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Page Title</label>
                <input name="title" type="text" value="{{ old('title', $page->title) }}"
                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Slug</label>
                <input name="slug" type="text" value="{{ old('slug', $page->slug) }}"
                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none font-mono text-sm transition-all" />
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Content</label>
                <textarea id="summernote-content" name="content">{{ old('content', $page->content) }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Meta Description</label>
                <input name="meta_description" type="text" value="{{ old('meta_description', $page->meta_description) }}"
                    placeholder="SEO description (max 160 chars)"
                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                <p class="text-xs text-on-surface-variant" id="meta-counter">0 / 160</p>
            </div>

            <div class="flex items-center gap-3">
                <input id="is_published" name="is_published" type="checkbox" value="1"
                    {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" />
                <label for="is_published" class="text-sm font-semibold text-on-surface-variant">Published</label>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-outline-variant/10">
                <a href="{{ route('admin.cms.pages.index') }}" class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
                <button type="submit"
                    class="px-8 py-3 bg-primary text-white font-bold rounded-DEFAULT shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(function () {
    // Summernote
    $('#summernote-content').summernote({
        placeholder: 'Write your page content here...',
        tabsize: 2,
        height: 380,
        toolbar: [
            ['style',  ['style']],
            ['font',   ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['para',   ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'hr']],
            ['view',   ['fullscreen', 'codeview']],
        ],
        styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
        callbacks: {
            onInit: function () {
                $('.note-editable').css('outline', 'none');
            }
        }
    });

    // Meta description counter
    const metaInput = document.querySelector('[name="meta_description"]');
    const counter   = document.getElementById('meta-counter');
    if (metaInput && counter) {
        counter.textContent = metaInput.value.length + ' / 160';
        metaInput.addEventListener('input', () => {
            counter.textContent = metaInput.value.length + ' / 160';
        });
    }
});
</script>
@endpush
