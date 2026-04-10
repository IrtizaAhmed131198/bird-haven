<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCodeMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    /** Show the OTP verification page */
    public function show(): View|RedirectResponse
    {
        if (! session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('pages.two-factor');
    }

    /** Resend a fresh OTP code */
    public function resend(): RedirectResponse
    {
        $userId = session('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);
        $this->sendCode($user);

        return back()->with('resent', 'A new code has been sent to your email.');
    }

    /** Verify the submitted OTP */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);

        // Check expiry
        if (! $user->two_factor_expires_at || now()->isAfter($user->two_factor_expires_at)) {
            return back()->withErrors(['code' => 'Your code has expired. Please request a new one.']);
        }

        // Check code
        if ($request->code !== $user->two_factor_code) {
            return back()->withErrors(['code' => 'Invalid code. Please try again.']);
        }

        // Success — clear the code, log in, mark session verified
        $user->update([
            'two_factor_code'       => null,
            'two_factor_expires_at' => null,
        ]);

        auth()->login($user);

        session()->forget('2fa_user_id');
        session(['2fa_verified' => true]);

        return redirect()->intended(route('home'))
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /** Toggle 2FA on/off via AJAX */
    public function toggle(Request $request)
    {
        $user    = auth()->user();
        $enabled = ! $user->two_factor_enabled;

        $user->update(['two_factor_enabled' => $enabled]);

        return response()->json([
            'enabled' => $enabled,
            'message' => $enabled
                ? 'Two-Factor Authentication enabled. A code will be sent to your email on each login.'
                : 'Two-Factor Authentication disabled.',
        ]);
    }

    /** Generate and email a 6-digit OTP */
    public static function sendCode(\App\Models\User $user): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'two_factor_code'       => $code,
            'two_factor_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new TwoFactorCodeMail($user));
    }
}
