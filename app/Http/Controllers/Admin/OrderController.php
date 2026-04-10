<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        $row = Order::selectRaw("
            COUNT(*) as total,
            SUM(status = 'preparing') as pending,
            SUM(status = 'transit') as transit,
            SUM(status IN ('delivered','arrived')) as delivered,
            SUM(CASE WHEN status != 'cancelled' THEN total ELSE 0 END) as revenue
        ")->first();

        $stats = [
            'total'     => (int) $row->total,
            'pending'   => (int) $row->pending,
            'transit'   => (int) $row->transit,
            'delivered' => (int) $row->delivered,
            'revenue'   => (float) $row->revenue,
        ];

        return view('admin.pages.orders.index', compact('stats'));
    }

    public function getData(Request $request)
    {
        $query = Order::with('user')->select('orders.*');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        return DataTables::eloquent($query)
            ->addColumn('customer_name',  fn ($o) => $o->user?->name ?? 'Guest')
            ->addColumn('customer_email', fn ($o) => $o->user?->email ?? '—')
            ->addColumn('total_fmt',      fn ($o) => '$' . number_format($o->total, 2))
            ->addColumn('date',           fn ($o) => $o->created_at->format('d M Y'))
            ->addColumn('actions',        fn ($o) => ['showUrl' => route('admin.orders.show', $o)])
            ->filterColumn('customer_name', function ($q, $keyword) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$keyword}%")
                                                   ->orWhere('email', 'like', "%{$keyword}%"))
                  ->orWhere('order_number', 'like', "%{$keyword}%");
            })
            ->make(true);
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.bird', 'items.accessory', 'shipment');

        return view('admin.pages.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'required|in:preparing,transit,arrived,delivered,cancelled',
            'status_message' => 'nullable|string|max:255',
        ]);

        $order->update([
            'status'         => $request->status,
            'status_message' => $request->status_message,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,awaiting_verification,failed,refunded',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Payment status updated.');
    }
}
