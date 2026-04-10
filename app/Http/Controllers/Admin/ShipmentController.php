<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ShipmentUpdateMail;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    private const STAGES = [
        'hatchery'  => 'Hatchery Preparation',
        'health'    => 'Health Clearance',
        'in_flight' => 'In Flight',
        'local'     => 'Local Sanctuary',
        'delivered' => 'Delivered',
    ];

    public function index()
    {
        return view('admin.pages.shipments.index');
    }

    public function getData(Request $request)
    {
        $query = Shipment::with('order', 'user');

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        return DataTables::eloquent($query)
            ->addColumn('order_number', fn ($s) => $s->order?->order_number ?? '—')
            ->addColumn('customer',     fn ($s) => $s->user?->name ?? '—')
            ->addColumn('stage_label',  fn ($s) => self::STAGES[$s->stage] ?? ucfirst($s->stage))
            ->addColumn('est_delivery', fn ($s) => $s->estimated_delivery?->format('M d, Y') ?? '—')
            ->addColumn('created_fmt',  fn ($s) => $s->created_at->format('M d, Y'))
            ->addColumn('actions', function ($s) {
                $editUrl   = route('admin.shipments.edit', $s->id);
                $deleteUrl = route('admin.shipments.destroy', $s->id);
                return "
                    <div class='flex items-center gap-2'>
                        <a href='{$editUrl}' class='p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-primary transition-colors' title='Edit'>
                            <span class='material-symbols-outlined text-lg'>edit</span>
                        </a>
                        <button onclick=\"confirmDelete('{$deleteUrl}')\"
                            class='p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors' title='Delete'>
                            <span class='material-symbols-outlined text-lg'>delete</span>
                        </button>
                    </div>
                ";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $orders = Order::with('user')
            ->whereDoesntHave('shipment')
            ->latest()
            ->get();

        $selectedOrder = $request->filled('order_id')
            ? Order::with('user')->find($request->order_id)
            : null;

        $stages = self::STAGES;

        return view('admin.pages.shipments.create', compact('orders', 'selectedOrder', 'stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'          => ['required', 'exists:orders,id'],
            'stage'             => ['required', 'in:' . implode(',', array_keys(self::STAGES))],
            'temperature'       => ['nullable', 'string', 'max:20'],
            'oxygen'            => ['nullable', 'string', 'max:20'],
            'estimated_delivery'=> ['nullable', 'date'],
            'hatchery_date'     => ['nullable', 'date'],
            'health_date'       => ['nullable', 'date'],
            'flight_date'       => ['nullable', 'date'],
            'local_date'        => ['nullable', 'date'],
            'delivery_date'     => ['nullable', 'date'],
            'notify_customer'   => ['nullable', 'boolean'],
        ]);

        $order = Order::with('user')->findOrFail($request->order_id);

        $shipment = Shipment::create([
            'order_id'          => $order->id,
            'user_id'           => $order->user_id,
            'stage'             => $request->stage,
            'temperature'       => $request->temperature,
            'oxygen'            => $request->oxygen,
            'estimated_delivery'=> $request->estimated_delivery,
            'hatchery_date'     => $request->hatchery_date,
            'health_date'       => $request->health_date,
            'flight_date'       => $request->flight_date,
            'local_date'        => $request->local_date,
            'delivery_date'     => $request->delivery_date,
        ]);

        if ($request->boolean('notify_customer') && $order->user?->email) {
            $shipment->load('order', 'user');
            Mail::to($order->user->email)->send(new ShipmentUpdateMail($shipment));
        }

        return redirect()->route('admin.shipments.edit', $shipment)
            ->with('success', 'Shipment created successfully.');
    }

    public function edit(Shipment $shipment)
    {
        $shipment->load('order.user');
        $stages = self::STAGES;

        return view('admin.pages.shipments.edit', compact('shipment', 'stages'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'stage'             => ['required', 'in:' . implode(',', array_keys(self::STAGES))],
            'temperature'       => ['nullable', 'string', 'max:20'],
            'oxygen'            => ['nullable', 'string', 'max:20'],
            'estimated_delivery'=> ['nullable', 'date'],
            'hatchery_date'     => ['nullable', 'date'],
            'health_date'       => ['nullable', 'date'],
            'flight_date'       => ['nullable', 'date'],
            'local_date'        => ['nullable', 'date'],
            'delivery_date'     => ['nullable', 'date'],
            'notify_customer'   => ['nullable', 'boolean'],
        ]);

        $stageChanged = $shipment->stage !== $request->stage;

        $shipment->update([
            'stage'             => $request->stage,
            'temperature'       => $request->temperature,
            'oxygen'            => $request->oxygen,
            'estimated_delivery'=> $request->estimated_delivery,
            'hatchery_date'     => $request->hatchery_date,
            'health_date'       => $request->health_date,
            'flight_date'       => $request->flight_date,
            'local_date'        => $request->local_date,
            'delivery_date'     => $request->delivery_date,
        ]);

        if ($request->boolean('notify_customer') && $shipment->user?->email) {
            $shipment->load('order', 'user');
            Mail::to($shipment->user->email)->send(new ShipmentUpdateMail($shipment));
        }

        return back()->with('success', 'Shipment updated' . ($stageChanged ? ' and customer notified.' : '.'));
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Shipment deleted.');
    }
}
