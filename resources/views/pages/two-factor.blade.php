@extends('layouts.app')

@section('title', 'Verify Login | Bird Haven')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-surface-container-low px-4 py-16">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-2xl font-headline font-extrabold text-primary tracking-tight">Bird Haven</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.08)] p-8">

            {{-- Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings:'FILL' 1;">mark_email_read</span>
                </div>
            </div>

            <h1 class="text-2xl font-headline font-extrabold text-center text-on-surface mb-2">Check your email</h1>
            <p class="text-sm text-on-surface-variant text-center mb-8 leading-relaxed">
                We sent a 6-digit verification code to your email address. Enter it below to complete your login.
            </p>

            {{-- Success alerts --}}
            @if(session('resent'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 text-center">
                    {{ session('resent') }}
                </div>
            @endif

            {{-- Error --}}
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- OTP Form --}}
            <form action="{{ route('2fa.verify') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-3 text-center">Verification Code</label>
                    {{-- 6 individual digit inputs for UX --}}
                    <div class="flex gap-2 justify-center" id="otp-inputs">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                class="otp-digit w-12 h-14 text-center text-2xl font-bold bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary outline-none transition-all"
                                autocomplete="off" />
                        @endfor
                    </div>
                    {{-- Hidden input that holds the combined code --}}
                    <input type="hidden" name="code" id="otp-combined" />
                </div>

                <button type="submit"
                    class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg hover:-translate-y-0.5 transition-all">
                    Verify & Login
                </button>
            </form>

            {{-- Resend --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-on-surface-variant mb-2">Didn't receive the code?</p>
                <form action="{{ route('2fa.resend') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-primary font-semibold text-sm hover:underline">
                        Resend Code
                    </button>
                </form>
                <span class="text-on-surface-variant text-sm mx-2">&middot;</span>
                <a href="{{ route('login') }}" class="text-on-surface-variant text-sm hover:text-on-surface">Back to Login</a>
            </div>

        </div>

        {{-- Expiry notice --}}
        <p class="text-center text-xs text-on-surface-variant mt-6">Code expires in <span id="countdown" class="font-bold text-primary">5:00</span></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── OTP digit input navigation ─────────────────────────────────────────────
const digits  = document.querySelectorAll('.otp-digit');
const combined = document.getElementById('otp-combined');

digits.forEach((input, idx) => {
    input.addEventListener('input', function () {
        // Keep only digits
        this.value = this.value.replace(/[^0-9]/g, '').slice(-1);

        if (this.value && idx < digits.length - 1) {
            digits[idx + 1].focus();
        }

        syncCombined();
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && ! this.value && idx > 0) {
            digits[idx - 1].focus();
        }
    });

    // Handle paste on any digit
    input.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
        pasted.split('').forEach((char, i) => {
            if (digits[idx + i]) digits[idx + i].value = char;
        });
        const nextEmpty = [...digits].findIndex(d => !d.value);
        if (nextEmpty !== -1) digits[nextEmpty].focus();
        syncCombined();
    });
});

function syncCombined() {
    combined.value = [...digits].map(d => d.value).join('');
}

// Auto-focus first input
digits[0]?.focus();

// ── Countdown timer ────────────────────────────────────────────────────────
let seconds = 300;
const countdown = document.getElementById('countdown');

const timer = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
        clearInterval(timer);
        countdown.textContent = 'Expired';
        countdown.style.color = '#ef4444';
        return;
    }
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    countdown.textContent = m + ':' + String(s).padStart(2, '0');
}, 1000);
</script>
@endpush
