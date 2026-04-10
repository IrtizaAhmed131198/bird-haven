@extends('layouts.auth')

@section('title', 'Reset Password | Bird Haven')

@section('content')

    {{-- ===== DESKTOP LAYOUT ===== --}}
    <div class="hidden md:block">
        <div class="relative min-h-screen flex flex-col overflow-hidden">

            {{-- Background --}}
            <div class="absolute inset-0 z-0">
                <img alt="" class="w-full h-full object-cover opacity-10 blur-[2px]" src="{{ asset('assets/images/banner.png') }}" />
                <div class="absolute inset-0 bg-gradient-to-tr from-surface via-transparent to-primary/5"></div>
            </div>

            {{-- Main Content --}}
            <div class="relative z-10 flex-1 flex items-center justify-center py-12 px-4">
                <div class="w-full max-w-[480px]">
                    <div class="bg-surface-container-lowest rounded-xl p-10 shadow-[0_20px_40px_rgba(25,28,29,0.06)] border border-outline-variant/10">

                        {{-- Logo --}}
                        <div class="flex justify-center mb-8">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="h-10 w-auto" />
                            </a>
                        </div>

                        {{-- Icon --}}
                        <div class="flex justify-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-3xl">lock_reset</span>
                            </div>
                        </div>

                        <h1 class="font-headline text-2xl font-bold text-on-surface text-center mb-2">Forgot your password?</h1>
                        <p class="text-on-surface-variant text-sm text-center mb-8 leading-relaxed">
                            No worries — enter your email address and we'll send you a reset link.
                        </p>

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-3">
                                <span class="material-symbols-outlined text-emerald-600 text-lg shrink-0 mt-0.5">check_circle</span>
                                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-on-surface-variant ml-1">Email Address</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">mail</span>
                                    <input name="email" type="email" value="{{ old('email') }}"
                                        placeholder="your@email.com" autofocus
                                        class="w-full pl-12 pr-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none text-on-surface" />
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-primary text-on-primary font-bold py-4 rounded-full shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2">
                                <span>Send Reset Link</span>
                                <span class="material-symbols-outlined text-lg">send</span>
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-1.5 text-sm text-on-surface-variant hover:text-primary transition-colors font-medium">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                Back to Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <main class="md:hidden min-h-screen flex flex-col px-6 pt-16 pb-10 max-w-[390px] mx-auto">

        <div class="flex justify-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="h-9 w-auto" />
            </a>
        </div>

        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-3xl">lock_reset</span>
            </div>
        </div>

        <h1 class="font-headline text-2xl font-bold text-on-surface text-center mb-2">Forgot your password?</h1>
        <p class="text-on-surface-variant text-sm text-center mb-8">Enter your email and we'll send a reset link.</p>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-3">
                <span class="material-symbols-outlined text-emerald-600 text-lg shrink-0 mt-0.5">check_circle</span>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant px-2">Email Address</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">mail</span>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="guardian@sanctuary.org"
                        class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-outline-variant shadow-sm transition-all duration-300 outline-none" />
                </div>
            </div>

            <button type="submit"
                class="w-full py-5 bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-bold text-lg rounded-full shadow-[0_10px_25px_rgba(0,101,139,0.25)] active:scale-95 transition-all duration-300 mt-2">
                Send Reset Link
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-1.5 text-sm text-on-surface-variant hover:text-primary transition-colors font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to Sign In
            </a>
        </div>
    </main>

@endsection
