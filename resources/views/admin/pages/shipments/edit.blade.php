@extends('layouts.admin')

@section('title', 'Shipment ' . $shipment->tracking_number . ' | BirdHaven Admin')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.shipments.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">{{ $shipment->tracking_number }}</h2>
            <p class="text-on-surface-variant mt-1">
                Order #{{ $shipment->order?->order_number }}
                @if ($shipment->order)
                    &mdash;
                    <a href="{{ route('admin.orders.show', $shipment->order->id) }}" class="text-primary hover:underline">View Order</a>
                @endif
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            @foreach ($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Stage Progress Bar --}}
    @php
        $stageKeys   = array_keys($stages);
        $currentIdx  = array_search($shipment->stage, $stageKeys) ?? 0;
    @endphp
    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6 mb-6">
        <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-4">Journey Progress</p>
        <div class="flex items-center gap-0">
            @foreach ($stages as $key => $label)
                @php $idx = array_search($key, $stageKeys); $done = $idx <= $currentIdx; $active = $idx === $currentIdx; @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 relative">
                    {{-- Connector line --}}
                    @if (!$loop->last)
                        <div class="absolute top-4 left-1/2 w-full h-0.5 {{ $done && !$active ? 'bg-primary' : 'bg-slate-200' }}" style="z-index:0;"></div>
                    @endif
                    {{-- Dot --}}
                    <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $active ? 'bg-primary text-white ring-4 ring-primary/20' : ($done ? 'bg-primary/20 text-primary' : 'bg-slate-100 text-slate-400') }}">
                        @if ($done && !$active)
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">check</span>
                        @else
                            {{ $idx + 1 }}
                        @endif
                    </div>
                    <span class="text-[10px] font-semibold text-center leading-tight max-w-[60px]
                        {{ $active ? 'text-primary' : ($done ? 'text-on-surface' : 'text-slate-400') }}">
                        {{ $label }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-8">
        <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            {{-- Customer Info (read-only) --}}
            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-outline-variant/10">
                <div>
                    <p class="text-xs font-semibold text-on-surface-variant mb-1">Customer</p>
                    <p class="text-sm font-bold text-on-surface">{{ $shipment->user?->name ?? '—' }}</p>
                    <p class="text-xs text-on-surface-variant">{{ $shipment->user?->email ?? '' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-on-surface-variant mb-1">Tracking Number</p>
                    <p class="text-sm font-mono font-bold text-primary">{{ $shipment->tracking_number }}</p>
                </div>
            </div>

            {{-- Stage --}}
            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Current Stage</label>
                <select name="stage" required
                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    @foreach ($stages as $key => $label)
                        <option value="{{ $key }}" {{ old('stage', $shipment->stage) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Stage Dates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @php
                    $dateCols = [
                        'hatchery_date' => ['label' => 'Hatchery Date',          'field' => 'hatchery_date'],
                        'health_date'   => ['label' => 'Health Clearance Date',  'field' => 'health_date'],
                        'flight_date'   => ['label' => 'Flight Date',            'field' => 'flight_date'],
                        'local_date'    => ['label' => 'Local Arrival Date',     'field' => 'local_date'],
                        'delivery_date' => ['label' => 'Delivery Date',          'field' => 'delivery_date'],
                    ];
                @endphp
                @foreach ($dateCols as $col)
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-on-surface-variant">{{ $col['label'] }}</label>
                        <input type="date" name="{{ $col['field'] }}"
                            value="{{ old($col['field'], $shipment->{$col['field']}?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                    </div>
                @endforeach
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-on-surface-variant">Estimated Delivery</label>
                    <input type="date" name="estimated_delivery"
                        value="{{ old('estimated_delivery', $shipment->estimated_delivery?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                </div>
            </div>

            {{-- Telemetry --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-on-surface-variant">Temperature</label>
                    <input type="text" name="temperature" value="{{ old('temperature', $shipment->temperature) }}" placeholder="e.g. 26°C"
                        class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-on-surface-variant">Oxygen</label>
                    <input type="text" name="oxygen" value="{{ old('oxygen', $shipment->oxygen) }}" placeholder="e.g. 21%"
                        class="w-full px-4 py-2.5 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" />
                </div>
            </div>

            {{-- Notify Customer --}}
            <div class="flex items-center gap-3 pt-2 pb-2 bg-amber-50 border border-amber-200 rounded-xl px-4">
                <input id="notify_customer" name="notify_customer" type="checkbox" value="1"
                    class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" />
                <label for="notify_customer" class="text-sm font-semibold text-amber-800 flex-1 py-3">
                    Send shipment update email to customer
                    <span class="block text-xs font-normal text-amber-700">{{ $shipment->user?->email }}</span>
                </label>
                <span class="material-symbols-outlined text-amber-500">email</span>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-outline-variant/10">
                <button type="button" onclick="confirmDelete('{{ route('admin.shipments.destroy', $shipment) }}')"
                    class="px-4 py-2.5 text-red-500 hover:text-red-700 font-semibold text-sm flex items-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-lg">delete</span> Delete Shipment
                </button>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.shipments.index') }}"
                        class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 bg-primary text-white font-bold rounded-DEFAULT shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function confirmDelete(url) {
    Swal.fire({
        title: 'Delete Shipment?',
        text: 'This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Delete',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `<input name="_token" value="{{ csrf_token() }}"><input name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
