<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\ContactThankYouMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key');

        $contactChannels = [
            [
                'icon'  => 'call',
                'label' => 'Phone',
                'value' => $settings['contact_phone'] ?? '+92 300 0000000',
                'href'  => 'tel:' . preg_replace('/[^+0-9]/', '', $settings['contact_phone'] ?? ''),
            ],
            [
                'icon'  => 'mail',
                'label' => 'Email',
                'value' => $settings['contact_email'] ?? 'hello@birdhaven.pk',
                'href'  => 'mailto:' . ($settings['contact_email'] ?? 'hello@birdhaven.pk'),
            ],
            [
                'icon'  => 'location_on',
                'label' => 'Location',
                'value' => $settings['contact_address'] ?? 'Karachi, Pakistan',
                'href'  => 'https://maps.google.com/?q=' . urlencode($settings['contact_address'] ?? 'Karachi Pakistan'),
            ],
        ];

        $hours = [
            ['day' => 'Monday – Friday', 'time' => $settings['hours_weekday'] ?? '9:00 AM – 6:00 PM'],
            ['day' => 'Saturday',        'time' => $settings['hours_saturday'] ?? '10:00 AM – 4:00 PM'],
            ['day' => 'Sunday',          'time' => $settings['hours_sunday'] ?? 'Closed'],
        ];

        $address = $settings['contact_address'] ?? '';

        return view('pages.contact', compact('contactChannels', 'hours', 'address'));
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'full_name'  => 'nullable|string|max:200',
            'email'      => 'required|email',
            'topic'      => 'nullable|string|max:150',
            'subject'    => 'nullable|string|max:150',
            'message'    => 'required|string|min:5|max:2000',
        ]);

        // Resolve name from whichever fields were submitted
        $name = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''))
            ?: ($data['full_name'] ?? 'Friend');

        // Save to database
        $contactMessage = ContactMessage::create([
            'name'    => $name,
            'email'   => $data['email'],
            'topic'   => $data['topic'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
        ]);

        // 1. Admin notification email
        $adminEmail = Setting::where('key', 'contact_email')->value('value')
            ?? config('mail.from.address');

        Mail::to($adminEmail)->send(new ContactMail([
            'name'    => $name,
            'email'   => $data['email'],
            'subject' => $data['topic'] ?? $data['subject'] ?? 'General Inquiry',
            'message' => $data['message'],
        ]));

        // 2. Thank-you email to the sender
        Mail::to($data['email'])->send(new ContactThankYouMail($contactMessage));

        return back()->with('success', 'Your message has been sent. Our team will respond within 24 hours.');
    }
}
