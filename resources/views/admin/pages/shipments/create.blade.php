@extends('layouts.admin')

@section('title', 'New Shipment | BirdHaven Admin')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.shipments.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">New Shipment</h2>
            <p class="text-on-surface-variant mt-1">Create a shipment and notify the customer.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            @foreach ($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-8">
        <form action="{{ route('admin.shipments.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Order Selection --}}
            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Order</label>
                @if ($selectedOrder)
                    <div class="flex items-center justify-between px-4 py-3 bg-surface-container rounded-DEFAULT ring-1 ring-primary/30">
                        <div>
                            <span class="font-bold text-on-surface">#{{ $selectedOrder->order_number }}</span>
                            <span class="text-on-surface-variant text-sm ml-2">— {{ $selectedOrder->user?->name }}</span>
                        </div>
                        <span class="text-xs font-semibold text-primary">Pre-selected</span>
                    </div>
                    <input type="hidden" name="order_id" value="{{ $selectedOrder->id }}" />
                @else
                    <select name="order_id" required
                        class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="">Select an order…</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                #{{ $order->order_number }} — {{ $order->user?->name }}
                                ({{ $order->created_at->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                    @if ($orders->isEmpty())
                        <p class="text-xs text-amber-600">All orders already have a shipment assigned.</p>
                    @endif
                @endif
            </div>

            {{-- Stage --}}
            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Current Stage</label>
                <select name="stage" id="stage-select" required
                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    @foreach ($stages as $key => $label)
                        <option value="{{ $key }}" {{ old('stage', 'hatchery') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Stage Dates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @php
                    $dateCols = [
                        'hatchery_date' => 'Hatchery Date',
                        'health_date'   => 'Health Clearance Date',
                        'flight_date'   => 'Flight Date',
                        'local_date'    => 'Local Arrival Date',
                        'delivery_date' => 'Delivery Date',
                    ];
                @endphp
                @foreach ($dateCols as $field => $label)
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-on-surface-variant">{{ $label }}</label>
                        <input type="date" name="{{ $field }}" value="{{ old($field) }}"
                            class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                    </div>
                @endforeach
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-on-surface-variant">Estimated Delivery</label>
                    <input type="date" name="estimated_delivery" value="{{ old('estimated_delivery') }}"
                        class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                </div>
            </div>

            {{-- Telemetry --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-on-surface-variant">Temperature</label>
                    <input type="text" name="temperature" value="{{ old('temperature') }}" placeholder="e.g. 26°C"
                        class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-on-surface-variant">Oxygen</label>
                    <input type="text" name="oxygen" value="{{ old('oxygen') }}" placeholder="e.g. 21%"
                        class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                </div>
            </div>

            {{-- Notify Customer --}}
            <div class="flex items-center gap-3 pt-2">
                <input id="notify_customer" name="notify_customer" type="checkbox" value="1"
                    {{ old('notify_customer', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" />
                <label for="notify_customer" class="text-sm font-semibold text-on-surface-variant">
                    Send shipment notification email to customer
                </label>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-outline-variant/10">
                <a href="{{ route('admin.shipments.index') }}"
                    class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
                <button type="submit"
                    class="px-8 py-3 bg-primary text-white font-bold rounded-DEFAULT shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Create Shipment
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
