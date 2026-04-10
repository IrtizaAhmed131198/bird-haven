<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Bird;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $shipping = $subtotal > 0 ? 49.00 : 0.00;
        $tax      = round($subtotal * 0.08, 2);
        $total    = $subtotal + $shipping + $tax;

        return view('pages.cart', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        // Accept either bird_id or accessory_id
        $request->validate([
            'bird_id'      => ['nullable', 'exists:birds,id'],
            'accessory_id' => ['nullable', 'exists:accessories,id'],
        ]);

        if ($request->filled('bird_id')) {
            $product   = Bird::findOrFail($request->bird_id);
            $key       = ['bird_id' => $product->id, 'accessory_id' => null];
            $sessionKey = 'bird_' . $product->id;
        } elseif ($request->filled('accessory_id')) {
            $product   = Accessory::findOrFail($request->accessory_id);
            $key       = ['bird_id' => null, 'accessory_id' => $product->id];
            $sessionKey = 'acc_' . $product->id;
        } else {
            return back()->with('error', 'No product selected.');
        }

        if (auth()->check()) {
            $item = CartItem::where('user_id', auth()->id())
                ->where($key)
                ->first();

            if ($item) {
                $item->increment('quantity');
            } else {
                CartItem::create(array_merge(['user_id' => auth()->id(), 'quantity' => 1], $key));
            }
        } else {
            $cart = session()->get('cart', []);
            $cart[$sessionKey] = array_merge($key, [
                'quantity' => ($cart[$sessionKey]['quantity'] ?? 0) + 1,
            ]);
            session()->put('cart', $cart);
        }

        return back()->with('success', $product->name . ' added to your cart.');
    }

    public function update(Request $request, int $item): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        if (auth()->check()) {
            $cartItem = CartItem::where('user_id', auth()->id())->findOrFail($item);

            if ($request->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $request->quantity]);
            }
        } else {
            $cart = session()->get('cart', []);
            // item id in session is the key string (bird_X or acc_X)
            if (isset($cart[$item])) {
                if ($request->quantity <= 0) {
                    unset($cart[$item]);
                } else {
                    $cart[$item]['quantity'] = $request->quantity;
                }
                session()->put('cart', $cart);
            }
        }

        return back();
    }

    public function remove(int $item): RedirectResponse
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->findOrFail($item)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart['bird_' . $item], $cart['acc_' . $item]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::with('bird.category', 'accessory')
                ->where('user_id', auth()->id())
                ->get();
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return collect();
        }

        $birdIds      = array_values(array_filter(array_column($cart, 'bird_id')));
        $accessoryIds = array_values(array_filter(array_column($cart, 'accessory_id')));

        $birds       = $birdIds      ? Bird::with('category')->whereIn('id', $birdIds)->get()->keyBy('id')      : collect();
        $accessories = $accessoryIds ? Accessory::whereIn('id', $accessoryIds)->get()->keyBy('id') : collect();

        return collect($cart)->map(function ($row, $key) use ($birds, $accessories) {
            if (!empty($row['bird_id'])) {
                $product = $birds->get($row['bird_id']);
                if (!$product) return null;
                return (object) ['id' => $key, 'bird' => $product, 'accessory' => null, 'quantity' => $row['quantity'], 'product' => $product];
            } else {
                $product = $accessories->get($row['accessory_id']);
                if (!$product) return null;
                return (object) ['id' => $key, 'bird' => null, 'accessory' => $product, 'quantity' => $row['quantity'], 'product' => $product];
            }
        })->filter()->values();
    }
}
