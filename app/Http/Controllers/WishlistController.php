<?php

namespace App\Http\Controllers;

use App\Models\Bird;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlistItems = Wishlist::with('bird.category')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pages.wishlist', compact('wishlistItems'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate(['bird_id' => 'required|exists:birds,id']);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'bird_id' => $request->bird_id,
        ]);

        return back()->with('success', 'Added to your wishlist.');
    }

    public function remove(int $item): RedirectResponse
    {
        Wishlist::where('user_id', auth()->id())
            ->findOrFail($item)
            ->delete();

        return back()->with('success', 'Removed from wishlist.');
    }
}
