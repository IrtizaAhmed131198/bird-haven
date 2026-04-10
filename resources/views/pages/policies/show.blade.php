@extends('layouts.app')

@section('title', $page->title . ' | Bird Haven')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-24">

    <div class="mb-10">
        <span class="text-xs font-bold uppercase tracking-widest text-primary">Legal</span>
        <h1 class="text-4xl font-extrabold font-headline text-on-surface mt-2 mb-3">{{ $page->title }}</h1>
        <p class="text-on-surface-variant text-sm">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
    </div>

    <div class="prose-policy space-y-6 text-on-surface-variant leading-relaxed">
        {!! $page->content !!}
    </div>

    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-wrap gap-4 text-sm">
        @if ($page->slug !== 'privacy-policy')
            <a href="{{ route('privacy') }}" class="text-primary hover:underline">Privacy Policy</a>
        @endif
        @if ($page->slug !== 'terms')
            <a href="{{ route('terms') }}" class="text-primary hover:underline">Terms of Service</a>
        @endif
        @if ($page->slug !== 'ethical-care')
            <a href="{{ route('ethical.care') }}" class="text-primary hover:underline">Ethical Care Agreement</a>
        @endif
        @if ($page->slug !== 'ethical-sourcing')
            <a href="{{ route('ethical.sourcing') }}" class="text-primary hover:underline">Ethical Sourcing</a>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .prose-policy h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #191c1d;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }
    .prose-policy p  { margin-bottom: 1rem; font-size: 0.95rem; }
    .prose-policy ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
    .prose-policy ul li { margin-bottom: 0.35rem; font-size: 0.9rem; }
    .prose-policy a  { color: #0c6780; text-decoration: underline; }
    .prose-policy strong { color: #191c1d; font-weight: 700; }
</style>
@endpush
