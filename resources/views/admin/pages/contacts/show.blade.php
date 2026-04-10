@extends('layouts.admin')

@section('title', 'Message from ' . $contact->name . ' | BirdHaven Admin')

@section('content')

<div class="flex items-center gap-3 mb-8">
    <a href="{{ route('admin.contacts.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-xl">arrow_back</span>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-on-surface font-headline tracking-tight">Message from {{ $contact->name }}</h1>
        <p class="text-on-surface-variant text-sm mt-0.5">Received {{ $contact->created_at->format('F d, Y \a\t h:i A') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Message --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">mail</span>
                    <h2 class="font-bold text-on-surface">Message</h2>
                </div>
                @if ($contact->topic)
                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">{{ $contact->topic }}</span>
                @endif
            </div>
            <div class="p-8">
                @if ($contact->subject)
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Subject</p>
                    <p class="text-on-surface font-semibold mb-6">{{ $contact->subject }}</p>
                @endif
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Message</p>
                <div class="bg-surface-container-low rounded-xl p-6 text-on-surface leading-relaxed whitespace-pre-wrap text-sm">{{ $contact->message }}</div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Sender info --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                <h2 class="font-bold text-on-surface">Sender</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg uppercase shrink-0">
                        {{ strtoupper(substr($contact->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-on-surface">{{ $contact->name }}</p>
                        <a href="mailto:{{ $contact->email }}" class="text-sm text-primary hover:underline">{{ $contact->email }}</a>
                    </div>
                </div>
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->topic ?? 'Your Enquiry' }}"
                    class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-base">reply</span>
                    Reply via Email
                </a>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                <h2 class="font-bold text-on-surface">Status</h2>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Read</span>
                    @if ($contact->is_read)
                        <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                            <span class="material-symbols-outlined text-base">check_circle</span> Yes
                        </span>
                    @else
                        <span class="flex items-center gap-1 text-amber-600 font-semibold">
                            <span class="material-symbols-outlined text-base">schedule</span> Unread
                        </span>
                    @endif
                </div>
                @if ($contact->read_at)
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Read At</span>
                    <span class="text-on-surface">{{ $contact->read_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Received</span>
                    <span class="text-on-surface">{{ $contact->created_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>
        </div>

        {{-- Delete --}}
        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
            onsubmit="return confirm('Permanently delete this message?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-100 transition-colors">
                <span class="material-symbols-outlined text-base">delete</span>
                Delete Message
            </button>
        </form>
    </div>
</div>

@endsection
