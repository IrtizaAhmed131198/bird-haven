@extends('layouts.auth')

@section('title', 'Join the Sanctuary | Bird Haven')

@section('content')

    {{-- ===== DESKTOP LAYOUT ===== --}}
    <div class="hidden md:block">
        <div class="relative min-h-screen flex flex-col overflow-hidden">

            {{-- Background --}}
            <div class="absolute inset-0 z-0">
                <img alt="" class="w-full h-full object-cover opacity-10 blur-[2px]" src="{{ asset('assets/images/banner.png') }}" />
                <div class="absolute inset-0 bg-gradient-to-tr from-surface via-transparent to-primary/5"></div>
            </div>

            {{-- Main content --}}
            <div class="relative z-10 flex-1 flex items-center justify-center py-12 px-4">
                <div class="w-full max-w-[1100px] flex gap-8 items-stretch">

                    {{-- Left: Brand & Feature Panel --}}
                    <div class="hidden md:flex flex-col justify-between w-1/2 p-12">
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="w-auto" style="height: 4.5rem" />
                            </div>
                            <div class="space-y-4 pt-8">
                                <h2 class="font-headline text-5xl font-bold leading-tight tracking-tight text-on-surface">
                                    Where ethical <br/><span class="text-primary italic">guardianship</span> begins.
                                </h2>
                                <p class="text-on-surface-variant text-lg max-w-md leading-relaxed">
                                    Join our global collective of avian enthusiasts. Access expert care journals, species documentation, and the curated sanctuary marketplace.
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 p-6 rounded-lg backdrop-blur-sm border border-white/20">
                                <span class="material-symbols-outlined text-secondary text-3xl mb-3">auto_awesome</span>
                                <h4 class="font-semibold text-on-surface">Curated Species</h4>
                                <p class="text-sm text-on-surface-variant">Detailed data for 500+ birds.</p>
                            </div>
                            <div class="bg-white/40 p-6 rounded-lg backdrop-blur-sm border border-white/20">
                                <span class="material-symbols-outlined text-primary text-3xl mb-3">verified_user</span>
                                <h4 class="font-semibold text-on-surface">Ethical Sourcing</h4>
                                <p class="text-sm text-on-surface-variant">Every action supports conservation.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Auth Form Card --}}
                    <div class="w-full md:w-1/2 flex items-center">
                        <div class="w-full bg-surface-container-lowest rounded-xl p-8 md:p-12 shadow-[0_20px_40px_rgba(25,28,29,0.06)] border border-outline-variant/10">
                            @php $showRegister = request()->routeIs('register'); @endphp

                            {{-- Tab Toggle --}}
                            <div class="flex items-center justify-between mb-10 bg-surface-container-low p-1.5 rounded-full">
                                <button id="desktop-tab-login"
                                    class="flex-1 py-3 px-6 rounded-full font-semibold transition-all duration-300 {{ !$showRegister ? 'text-on-primary bg-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                                    Sign In
                                </button>
                                <button id="desktop-tab-register"
                                    class="flex-1 py-3 px-6 rounded-full font-semibold transition-all duration-300 {{ $showRegister ? 'text-on-primary bg-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                                    Create Account
                                </button>
                            </div>

                            {{-- Login Form --}}
                            <div id="desktop-login-form" class="space-y-6 {{ $showRegister ? 'hidden' : '' }}">
                                <header>
                                    <h3 class="font-headline text-2xl font-bold text-on-surface">Welcome Back</h3>
                                    <p class="text-on-surface-variant text-sm mt-1">Enter your credentials to access the archive.</p>
                                </header>
                                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-on-surface-variant ml-1">Email Address</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">mail</span>
                                            <input name="email" type="email" placeholder="alex@aviary.com"
                                                class="w-full pl-12 pr-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none text-on-surface" />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center ml-1">
                                            <label class="text-sm font-medium text-on-surface-variant">Password</label>
                                            <a class="text-xs text-primary hover:underline font-medium" href="{{ route('password.request') }}">Forgot?</a>
                                        </div>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
                                            <input name="password" type="password" placeholder="••••••••"
                                                class="w-full pl-12 pr-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none text-on-surface" />
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 py-2">
                                        <input id="remember" name="remember" type="checkbox" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" />
                                        <label class="text-sm text-on-surface-variant" for="remember">Keep me signed in for 30 days</label>
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-primary text-on-primary font-bold py-4 rounded-full shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2">
                                        <span>Continue to Archive</span>
                                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                    </button>
                                </form>
                            </div>

                            {{-- Register Form --}}
                            <div id="desktop-register-form" class="space-y-6 {{ !$showRegister ? 'hidden' : '' }}">
                                <header>
                                    <h3 class="font-headline text-2xl font-bold text-on-surface">Create Your Account</h3>
                                    <p class="text-on-surface-variant text-sm mt-1">Join the sanctuary and begin your ethical journey.</p>
                                </header>
                                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-on-surface-variant ml-1">First Name</label>
                                            <input name="first_name" type="text" placeholder="Alex"
                                                class="w-full px-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-on-surface-variant ml-1">Last Name</label>
                                            <input name="last_name" type="text" placeholder="Vogel"
                                                class="w-full px-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none" />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-on-surface-variant ml-1">Email Address</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">mail</span>
                                            <input name="email" type="email" placeholder="alex@aviary.com"
                                                class="w-full pl-12 pr-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none" />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-on-surface-variant ml-1">Password</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
                                            <input name="password" type="password" placeholder="Create a strong password"
                                                class="w-full pl-12 pr-4 py-3.5 bg-surface-container rounded-md border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all outline-none" />
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 py-1">
                                        <input name="agree" type="checkbox" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" />
                                        <label class="text-sm text-on-surface-variant">I agree to the <a class="text-primary underline" href="{{ route('ethical.care') }}">Ethical Care Agreement</a> and <a class="text-primary underline" href="{{ route('privacy') }}">Privacy Policy</a></label>
                                    </div>
                                    <button type="submit" class="w-full bg-gradient-primary text-on-primary font-bold py-4 rounded-full shadow-lg shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2">
                                        <span>Join the Sanctuary</span>
                                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Desktop Footer --}}
            <footer class="relative z-10 bg-slate-50 w-full pt-20 pb-12">
                <div class="grid grid-cols-4 gap-12 px-12 max-w-[1440px] mx-auto">
                    <div class="space-y-4">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="h-9 w-auto" />
                        <p class="text-slate-500 text-sm">Empowering ethical avian enthusiasts with high-fidelity knowledge and community sanctuary.</p>
                    </div>
                    <div class="space-y-4">
                        <h5 class="text-sky-800 font-bold text-sm">Sanctuary</h5>
                        <ul class="space-y-2">
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="{{ route('shop') }}">Species Database</a></li>
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="#">Care Guidelines</a></li>
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="{{ route('shop') }}">The Marketplace</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h5 class="text-sky-800 font-bold text-sm">Support</h5>
                        <ul class="space-y-2">
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="{{ route('contact') }}">Contact</a></li>
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="#">Shipping</a></li>
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="#">Ethical Sourcing</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h5 class="text-sky-800 font-bold text-sm">Legal</h5>
                        <ul class="space-y-2">
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="{{ route('privacy') }}">Privacy Policy</a></li>
                            <li><a class="text-slate-500 hover:text-sky-600 text-sm" href="{{ route('terms') }}">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <div class="max-w-[1440px] mx-auto px-12 mt-12 pt-8 border-t border-slate-200/50">
                    <p class="text-slate-500 text-sm text-center">© {{ date('Y') }} Bird Haven. Ethical Avian Guardianship.</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <main class="md:hidden min-h-screen flex flex-col px-6 pt-12 pb-10 max-w-[390px] mx-auto overflow-x-hidden">

        {{-- Brand & Illustration --}}
        <section class="flex flex-col items-center mb-10">
            <div class="relative w-64 h-64 mb-6">
                <div class="absolute inset-0 bg-primary-container/20 rounded-full blur-3xl"></div>
                <img alt="Bird Illustration" class="relative w-full h-full object-contain drop-shadow-2xl z-10"
                    src="{{ asset('assets/images/about-banner-mobile.png') }}" />
            </div>
            <div class="text-center">
                <div class="flex justify-center mb-2">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="h-9 w-auto" />
                </div>
                <p class="text-on-surface-variant text-sm font-medium tracking-wide opacity-80">Ethical Avian Guardianship</p>
            </div>
        </section>

        {{-- Mobile Auth Form --}}
        <div class="flex-grow flex flex-col gap-6">
            <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant px-2" for="m-email">Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">mail</span>
                        <input id="m-email" name="email" type="email" placeholder="guardian@sanctuary.org"
                            class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-outline-variant shadow-sm transition-all duration-300 outline-none" />
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant px-2" for="m-password">Secret Key</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">lock</span>
                        <input id="m-password" name="password" type="password" placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-outline-variant shadow-sm transition-all duration-300 outline-none" />
                    </div>
                </div>
                <div class="flex justify-end px-2">
                    <a class="text-xs font-semibold text-primary hover:text-surface-tint transition-colors" href="{{ route('password.request') }}">Forgot your key?</a>
                </div>
                <div class="flex flex-col gap-4 mt-4">
                    <button type="submit" class="w-full py-5 bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-bold text-lg rounded-full shadow-[0_10px_25px_rgba(0,101,139,0.25)] active:scale-95 transition-all duration-300">
                        Sign In to Sanctuary
                    </button>
                </div>
            </form>
            <div class="flex items-center gap-4 px-4 py-2">
                <div class="h-[1px] flex-grow bg-outline-variant/30"></div>
                <span class="text-xs font-bold text-outline uppercase tracking-tighter">or create a nest</span>
                <div class="h-[1px] flex-grow bg-outline-variant/30"></div>
            </div>
            <a href="{{ route('register') }}" class="w-full py-5 bg-surface-container-high text-on-surface font-headline font-bold text-lg rounded-full active:scale-95 transition-all duration-300 text-center">
                Join the Collective
            </a>
        </div>

        {{-- Mobile Social + Legal --}}
        <footer class="mt-12">
            <div class="mt-10 text-center">
                <p class="text-[11px] text-outline leading-relaxed max-w-[240px] mx-auto">
                    By proceeding, you agree to our <a class="underline" href="{{ route('ethical.sourcing') }}">Ethical Sourcing Protocol</a> and <a class="underline" href="{{ route('privacy') }}">Guardian Privacy Policy</a>.
                </p>
            </div>
        </footer>
    </main>

@endsection

@push('scripts')
<script>
    const loginTab = document.getElementById('desktop-tab-login');
    const registerTab = document.getElementById('desktop-tab-register');
    const loginForm = document.getElementById('desktop-login-form');
    const registerForm = document.getElementById('desktop-register-form');

    function showLogin() {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('text-on-primary', 'bg-primary', 'shadow-sm');
        loginTab.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
        registerTab.classList.remove('text-on-primary', 'bg-primary', 'shadow-sm');
        registerTab.classList.add('text-on-surface-variant', 'hover:text-on-surface');
    }

    function showRegister() {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        registerTab.classList.add('text-on-primary', 'bg-primary', 'shadow-sm');
        registerTab.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
        loginTab.classList.remove('text-on-primary', 'bg-primary', 'shadow-sm');
        loginTab.classList.add('text-on-surface-variant', 'hover:text-on-surface');
    }

    if (loginTab) loginTab.addEventListener('click', showLogin);
    if (registerTab) registerTab.addEventListener('click', showRegister);
</script>
@endpush
