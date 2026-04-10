<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.pages.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_title'       => 'required|string|max:255',
            'site_tagline'     => 'nullable|string|max:255',
            'contact_email'    => 'nullable|email',
            'contact_phone'    => 'nullable|string|max:30',
            'contact_address'  => 'nullable|string|max:255',
            'hours_weekday'    => 'nullable|string|max:50',
            'hours_saturday'   => 'nullable|string|max:50',
            'hours_sunday'     => 'nullable|string|max:50',
            'footer_text'      => 'nullable|string|max:500',
            'facebook'         => 'nullable|url',
            'instagram'        => 'nullable|url',
            'twitter'          => 'nullable|url',
            'hero_title'       => 'nullable|string|max:255',
            'hero_subtitle'    => 'nullable|string|max:500',
            'hero_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $keys = ['site_title', 'site_tagline', 'contact_email', 'contact_phone', 'contact_address', 'hours_weekday', 'hours_saturday', 'hours_sunday', 'footer_text', 'facebook', 'instagram', 'twitter', 'hero_title', 'hero_subtitle'];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key, '')]
            );
        }

        // Handle hero image upload separately
        if ($request->hasFile('hero_image')) {
            $dir = public_path('uploads/images/hero');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Delete old hero image if it exists
            $old = Setting::where('key', 'hero_image')->value('value');
            if ($old && file_exists($dir . '/' . $old)) {
                unlink($dir . '/' . $old);
            }

            $file     = $request->file('hero_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($dir, $filename);

            Setting::updateOrCreate(['key' => 'hero_image'], ['value' => $filename]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
