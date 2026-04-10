<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $addresses = Address::where('user_id', auth()->id())
            ->orderByDesc('is_default')
            ->get();

        return view('pages.profile', compact('addresses'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();
        $data = $request->only('name', 'email');

        if ($request->hasFile('avatar')) {
            $dir = public_path('uploads/images/avatars');

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Delete old avatar if it exists
            if ($user->avatar && file_exists($dir . '/' . $user->avatar)) {
                unlink($dir . '/' . $user->avatar);
            }

            $file     = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($dir, $filename);

            $data['avatar'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function password(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
