<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('items.bird')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $stats = [
            'total_spent' => Order::where('user_id', auth()->id())->sum('total'),
            'order_count' => Order::where('user_id', auth()->id())->count(),
            'bird_count'  => Order::where('user_id', auth()->id())
                ->whereIn('status', ['delivered', 'arrived'])
                ->count(),
            'membership'  => 'Premium',
        ];

        return view('pages.orders', compact('orders', 'stats'));
    }

    public function show(int $order): View
    {
        $order = Order::with('items.bird.category')
            ->where('user_id', auth()->id())
            ->findOrFail($order);

        return view('pages.order-detail', compact('order'));
    }

    public function confirmation(int $order): View
    {
        $order = Order::with('items.bird')
            ->where('user_id', auth()->id())
            ->findOrFail($order);

        return view('pages.order-confirmation', compact('order'));
    }
}
