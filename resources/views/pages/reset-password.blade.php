@extends('layouts.auth')

@section('title', 'Set New Password | Bird Haven')

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

                        <div class="flex justify-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-3xl">key</span>
                            </div>
                        </div>

                        <h1 class="font-headline text-2xl font-bold text-on-surface text-center mb-2">Set New Password</h1>
                        <p class="text-on-surface-variant text-sm text-center mb-8">
                            Choose a strong password for your account.
                        </p>

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-on-surface-variant ml-1">Email Address</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">mail</span>
                                    <input name="email" type="email" value="{{ old('email', $email) }}"
                                        class="w-full pl-12 pr-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none text-on-surface" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-on-surface-variant ml-1">New Password</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
                                    <input name="password" type="password" id="new-password" placeholder="Create a strong password"
                                        class="w-full pl-12 pr-12 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none text-on-surface" />
                                    <button type="button" onclick="togglePwd('new-password','eye1')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                        <span id="eye1" class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-on-surface-variant ml-1">Confirm Password</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
                                    <input name="password_confirmation" type="password" id="confirm-password" placeholder="Repeat your password"
                                        class="w-full pl-12 pr-12 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none text-on-surface" />
                                    <button type="button" onclick="togglePwd('confirm-password','eye2')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                        <span id="eye2" class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-primary text-on-primary font-bold py-4 rounded-full shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2">
                                <span>Reset Password</span>
                                <span class="material-symbols-outlined text-lg">check</span>
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
                <span class="material-symbols-outlined text-primary text-3xl">key</span>
            </div>
        </div>

        <h1 class="font-headline text-2xl font-bold text-on-surface text-center mb-2">Set New Password</h1>
        <p class="text-on-surface-variant text-sm text-center mb-8">Choose a strong password for your account.</p>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant px-2">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">mail</span>
                    <input name="email" type="email" value="{{ old('email', $email) }}"
                        class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface shadow-sm transition-all outline-none" />
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant px-2">New Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
                    <input name="password" id="m-new-password" type="password" placeholder="Create a strong password"
                        class="w-full pl-12 pr-12 py-4 bg-surface-container-lowest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface shadow-sm transition-all outline-none" />
                    <button type="button" onclick="togglePwd('m-new-password','m-eye1')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant">
                        <span id="m-eye1" class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant px-2">Confirm Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
                    <input name="password_confirmation" id="m-confirm-password" type="password" placeholder="Repeat your password"
                        class="w-full pl-12 pr-12 py-4 bg-surface-container-lowest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface shadow-sm transition-all outline-none" />
                    <button type="button" onclick="togglePwd('m-confirm-password','m-eye2')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant">
                        <span id="m-eye2" class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full py-5 bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-bold text-lg rounded-full shadow-[0_10px_25px_rgba(0,101,139,0.25)] active:scale-95 transition-all duration-300 mt-2">
                Reset Password
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

@push('scripts')
<script>
    function togglePwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endpush
