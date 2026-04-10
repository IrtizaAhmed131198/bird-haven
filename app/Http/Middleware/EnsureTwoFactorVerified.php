<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->two_factor_enabled && ! session('2fa_verified')) {
            auth()->logout();
            return redirect()->route('2fa.show')
                ->with('error', 'Please complete Two-Factor Authentication.');
        }

        return $next($request);
    }
}
