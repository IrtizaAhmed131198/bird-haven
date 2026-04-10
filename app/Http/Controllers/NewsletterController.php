<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $existing = NewsletterSubscriber::where('email', $request->email)->first();

        if ($existing) {
            if (!$existing->is_active) {
                $existing->update(['is_active' => true, 'unsubscribed_at' => null]);
                return back()->with('newsletter_success', 'Welcome back! You\'ve been re-subscribed.');
            }
            return back()->with('newsletter_info', 'You\'re already subscribed — thank you!');
        }

        $subscriber = NewsletterSubscriber::create([
            'email'     => $request->email,
            'name'      => auth()->user()?->name,
            'is_active' => true,
        ]);

        Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));

        return back()->with('newsletter_success', 'You\'re subscribed! Check your inbox for a welcome email.');
    }
}
