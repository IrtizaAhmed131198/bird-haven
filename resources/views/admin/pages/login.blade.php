@extends('layouts.admin-auth')

@section('title', 'Admin Login | BirdHaven')

@section('content')

{{-- ===== DESKTOP LAYOUT (≥ md) ===== --}}
<div class="hidden md:flex min-h-screen items-center justify-center p-6 relative z-10">
    <main class="w-full max-w-[480px]">

        {{-- Glass Card --}}
        <div class="glass-card rounded-lg border border-surface-variant/15 shadow-[0_32px_64px_rgba(25,28,29,0.04)] p-12">

            {{-- Logo & Header --}}
            <div class="flex flex-col items-center mb-10">
                <div class="mb-6 h-16 w-16 overflow-hidden rounded-2xl bg-surface-container-low flex items-center justify-center">
                    <img alt="BirdHaven Logo" class="h-10 w-10 object-contain" src="{{ asset('assets/images/logo.png') }}" />
                </div>
                <h1 class="font-headline text-3xl font-extrabold text-on-surface tracking-tight mb-2">Guardian Console</h1>
                <p class="text-on-surface-variant text-center leading-relaxed text-sm">
                    Access the BirdHaven CMS terminal. Secure ornithological oversight starts here.
                </p>
            </div>

            {{-- Login Form --}}
            <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface-variant px-1" for="d-email">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <input id="d-email" name="email" type="email"
                            value="{{ old('email') }}"
                            placeholder="admin@birdhaven.com"
                            class="w-full pl-11 pr-4 py-4 bg-surface-container-highest/50 border-none rounded-DEFAULT focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all outline-none text-on-surface placeholder:text-outline/60" />
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-sm font-semibold text-on-surface-variant" for="d-password">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <input id="d-password" name="password" type="password"
                            placeholder="••••••••••••"
                            class="w-full pl-11 pr-4 py-4 bg-surface-container-highest/50 border-none rounded-DEFAULT focus:ring-1 focus:ring-primary focus:bg-surface-container-lowest transition-all outline-none text-on-surface placeholder:text-outline/60" />
                    </div>
                </div>

                <div class="flex items-center gap-2 px-1">
                    <input id="d-remember" name="remember" type="checkbox"
                        class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container" />
                    <label for="d-remember" class="text-sm text-on-surface-variant">Keep me signed in</label>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 btn-primary-gradient text-white font-headline font-bold rounded-DEFAULT shadow-lg shadow-primary/10 hover:shadow-primary/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span>Sign In</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-outline-variant/10 text-center">
                <p class="text-sm text-on-surface-variant">
                    Not an admin?
                    <a href="{{ route('home') }}" class="text-primary font-bold hover:underline ml-1">Go to Website</a>
                </p>
            </div>
        </div>

        {{-- Security Note --}}
        <div class="mt-8 flex flex-col items-center gap-4">
            <div class="flex items-center gap-4 text-outline/50 text-xs font-medium uppercase tracking-[0.2em]">
                <span>256-bit Encryption</span>
                <span class="h-1 w-1 rounded-full bg-outline/30"></span>
                <span>Protected Node</span>
            </div>
            <p class="text-[10px] text-outline/40 text-center max-w-[300px]">
                Authorized personnel only. All access attempts are logged and monitored.
            </p>
        </div>
    </main>
</div>

{{-- ===== MOBILE LAYOUT (< md) ===== --}}
<div class="md:hidden min-h-screen flex items-center justify-center p-6 relative z-10">
    <main class="w-full max-w-[420px]">

        {{-- Brand --}}
        <div class="flex flex-col items-center mb-10">
            <div class="w-16 h-16 mb-6 flex items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary-container shadow-xl">
                <span class="material-symbols-outlined text-white text-4xl">flutter_dash</span>
            </div>
            <h1 class="font-headline font-extrabold text-3xl tracking-tight text-on-surface mb-2">Guardian Console</h1>
            <p class="text-on-surface-variant font-medium text-sm">BirdHaven CMS Secure Login</p>
        </div>

        {{-- Login Card --}}
        <section class="glass-panel rounded-lg p-8 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)]">
            <div class="space-y-6">
                <div class="text-center mb-2">
                    <h2 class="font-headline font-bold text-xl text-on-surface">Welcome Back</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Enter your admin credentials to proceed.</p>
                </div>

                <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface ml-1" for="m-email">Admin Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                            <input id="m-email" name="email" type="email"
                                value="{{ old('email') }}"
                                placeholder="admin@birdhaven.com"
                                class="w-full bg-surface-container-highest border-none rounded-DEFAULT pl-12 pr-4 py-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface ml-1" for="m-password">Password</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                            <input id="m-password" name="password" type="password"
                                placeholder="••••••••"
                                class="w-full bg-surface-container-highest border-none rounded-DEFAULT pl-12 pr-12 py-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all outline-none" />
                            <button type="button" id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 px-1">
                        <input id="m-remember" name="remember" type="checkbox"
                            class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container" />
                        <label class="text-sm text-on-surface-variant font-medium select-none" for="m-remember">Remember this station</label>
                    </div>

                    <button type="submit"
                        class="w-full py-5 rounded-DEFAULT bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold text-lg shadow-[0_12px_24px_-8px_rgba(12,103,128,0.4)] hover:shadow-[0_16px_32px_-8px_rgba(12,103,128,0.5)] active:scale-[0.98] transition-all mt-2">
                        Sign In
                    </button>
                </form>
            </div>
        </section>

        <footer class="mt-8 text-center space-y-4">
            <div class="flex items-center justify-center space-x-2 text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                <span class="text-xs font-medium tracking-wide">ENCRYPTED CONNECTION</span>
            </div>
            <div class="pt-4 border-t border-outline-variant/20 flex justify-between items-center px-4">
                <p class="text-[10px] text-outline font-bold tracking-widest uppercase">System Status: Optimal</p>
                <a href="{{ route('home') }}" class="text-[10px] text-outline font-bold hover:text-primary transition-colors">← WEBSITE</a>
            </div>
        </footer>
    </main>
</div>

@endsection

@push('scripts')
<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('m-password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            this.querySelector('.material-symbols-outlined').textContent = isPassword ? 'visibility_off' : 'visibility';
        });
    }
</script>
@endpush
