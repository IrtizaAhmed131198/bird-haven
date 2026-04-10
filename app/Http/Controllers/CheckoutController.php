<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Accessory;
use App\Models\Bird;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\JazzCashService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('success', 'Your cart is empty. Please add some birds before checking out.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('pages.checkout', compact('cartItems', 'subtotal'));
    }

    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'email'          => 'required|email',
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'phone'          => 'required|string|max:20',
            'payment_method' => 'required|in:cod,jazzcash,bank_transfer',
            // JazzCash: customer's wallet number
            'jazzcash_number' => 'required_if:payment_method,jazzcash|nullable|string|max:20',
            // Bank transfer: receipt reference
            'transaction_id'  => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $total    = $subtotal;

        // ── JazzCash: call API before creating order ──────────────────────────
        $txnRef        = null;
        $paymentStatus = 'pending';

        if ($request->payment_method === 'jazzcash') {
            $jazzcash = new JazzCashService();

            // Normalise mobile number: strip dashes/spaces → 03001234567
            $mobile = preg_replace('/[^0-9]/', '', $request->jazzcash_number);

            $orderRef = 'BH-' . strtoupper(uniqid());

            $result = $jazzcash->charge(
                mobileNumber: $mobile,
                amount:       $subtotal,
                orderNumber:  $orderRef,
                description:  'Bird Haven Order'
            );

            if (! $result['success']) {
                return back()
                    ->withInput()
                    ->with('jazzcash_error', $result['message']);
            }

            $txnRef        = $result['txn_ref'];
            $paymentStatus = 'paid';

        } elseif ($request->payment_method === 'bank_transfer') {
            $txnRef        = $request->transaction_id;
            $paymentStatus = 'awaiting_verification';

        } else {
            // COD
            $paymentStatus = 'pending';
        }

        // ── Create order ──────────────────────────────────────────────────────
        $order = Order::create([
            'user_id'          => auth()->id(),
            'status'           => 'preparing',
            'payment_method'   => $request->payment_method,
            'payment_status'   => $paymentStatus,
            'transaction_id'   => $txnRef,
            'subtotal'         => $subtotal,
            'shipping'         => 0,
            'tax'              => 0,
            'total'            => $total,
            'shipping_name'    => $request->first_name . ' ' . $request->last_name,
            'shipping_address' => $request->address,
            'shipping_address2'=> $request->address2,
            'shipping_city'    => $request->city,
            'shipping_postal'  => $request->postal_code,
            'notes'            => $request->phone,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'bird_id'      => $item->bird?->id,
                'accessory_id' => $item->accessory?->id,
                'quantity'     => $item->quantity,
                'price'        => $item->product->price,
            ]);
        }

        // Clear cart
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('cart');
        }

        // Send order confirmation email to customer
        $order->load('items.bird', 'items.accessory', 'user');
        $recipient = $order->user?->email ?? $request->email;
        Mail::to($recipient)->send(new OrderConfirmationMail($order));

        // Notify admin
        $adminEmail = \App\Models\Setting::where('key', 'contact_email')->value('value')
            ?? config('mail.from.address');
        Mail::to($adminEmail)->send(new OrderConfirmationMail($order));

        return redirect()->route('order.confirmation', $order->id);
    }

    private function getCartItems()
    {
        return (new CartController)->getCartItems();
    }
}
