@extends('layouts.admin')

@section('title', 'Orders | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
    div.dataTables_wrapper div.dataTables_length select,
    div.dataTables_wrapper div.dataTables_filter input {
        border: 1px solid #bfc8cd; border-radius: .5rem;
        padding: .3rem .6rem; font-size: .85rem; outline: none;
    }
    div.dataTables_wrapper div.dataTables_filter input:focus {
        border-color: #0c6780; box-shadow: 0 0 0 2px rgba(12,103,128,.15);
    }
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter { font-size: .8rem; color: #3f484c; }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button {
        border-radius: .5rem !important; font-size: .8rem;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
        background: #0c6780 !important; color: #fff !important; border-color: #0c6780 !important;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
        background: #e6e8e9 !important; color: #0c6780 !important; border-color: transparent !important;
    }
    table.dataTable thead th {
        font-family: 'Manrope', sans-serif; font-weight: 700;
        font-size: .75rem; color: #3f484c;
        background: rgba(242,244,245,.6);
        border-bottom: 1px solid rgba(191,200,205,.2);
        padding: 1rem 1.25rem; text-transform: uppercase; letter-spacing: .08em;
    }
    table.dataTable tbody tr { transition: background .15s; }
    table.dataTable tbody tr:hover { background: rgba(186,234,255,.07) !important; }
    table.dataTable tbody td { vertical-align: middle; padding: 1rem 1.25rem; }
    table.dataTable.no-footer { border-bottom: none; }
    .active-filter   { background: rgba(12,103,128,.1); color: #0c6780; border-color: rgba(12,103,128,.2); }
    .inactive-filter { background: white; color: #3f484c; border-color: rgba(191,200,205,.3); }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
    <div>
        <h2 class="font-headline font-extrabold text-4xl text-on-surface tracking-tight">Orders</h2>
        <p class="text-on-surface-variant mt-2">Manage customer orders, update statuses, and track payments.</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    @php
    $statCards = [
        ['label' => 'Total Orders', 'value' => number_format($stats['total']),             'icon' => 'receipt_long',   'color' => 'sky'],
        ['label' => 'Preparing',    'value' => number_format($stats['pending']),            'icon' => 'pending',        'color' => 'amber'],
        ['label' => 'In Transit',   'value' => number_format($stats['transit']),            'icon' => 'local_shipping', 'color' => 'blue'],
        ['label' => 'Delivered',    'value' => number_format($stats['delivered']),          'icon' => 'check_circle',   'color' => 'emerald'],
        ['label' => 'Revenue',      'value' => '$'.number_format($stats['revenue'], 2),    'icon' => 'payments',       'color' => 'violet'],
    ];
    @endphp
    @foreach ($statCards as $card)
    <div class="bg-surface-container-lowest rounded-DEFAULT p-5 border border-outline-variant/10 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="material-symbols-outlined text-{{ $card['color'] }}-600 text-xl">{{ $card['icon'] }}</span>
            <span class="text-xs font-bold text-{{ $card['color'] }}-600 uppercase tracking-wider">{{ $card['label'] }}</span>
        </div>
        <p class="text-2xl font-headline font-bold text-on-surface">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="md:col-span-2 bg-surface-container-low rounded-DEFAULT p-4 flex flex-wrap items-center gap-4">
        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">filter_list</span>

        {{-- Status filter --}}
        <div class="flex gap-2 flex-wrap" id="status-filters">
            <button data-status="" class="status-btn active-filter px-3 py-1 rounded-full text-xs font-bold border transition-colors">All</button>
            <button data-status="preparing" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Preparing</button>
            <button data-status="transit"   class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Transit</button>
            <button data-status="arrived"   class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Arrived</button>
            <button data-status="delivered" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Delivered</button>
            <button data-status="cancelled" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Cancelled</button>
        </div>

        <div class="w-px h-5 bg-outline-variant/30 hidden md:block"></div>

        {{-- Payment method filter --}}
        <select id="filter-payment"
            class="bg-white border border-outline-variant/30 rounded-lg text-xs font-semibold text-on-surface py-2 px-3 outline-none focus:border-primary transition-colors">
            <option value="">All Payments</option>
            <option value="cod">COD</option>
            <option value="jazzcash">JazzCash</option>
            <option value="bank_transfer">Bank Transfer</option>
        </select>
    </div>
    <div class="bg-surface-container-low rounded-DEFAULT p-4 flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Orders</span>
        <span id="total-count" class="text-2xl font-headline font-black text-primary">—</span>
    </div>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest/80 backdrop-blur-xl rounded-lg shadow-xl shadow-on-surface/5 overflow-hidden border border-white/40">
    <div class="overflow-x-auto p-4">
        <table id="orders-table" class="w-full text-left">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(function () {
    let activeStatus  = '';
    let activePayment = '';

    const table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.orders.data') }}',
            data: d => {
                d.status         = activeStatus;
                d.payment_method = activePayment;
            }
        },
        columns: [
            {
                data: 'order_number', name: 'order_number',
                orderable: true, searchable: true,
                render: d => `<span class="font-headline font-bold text-primary">#${d}</span>`
            },
            {
                data: 'customer_name', name: 'customer_name',
                orderable: false, searchable: true,
                render: (data, type, row) => `
                    <div>
                        <div class="font-semibold text-on-surface text-sm">${data}</div>
                        <div class="text-xs text-on-surface-variant">${row.customer_email}</div>
                    </div>`
            },
            {
                data: 'date', name: 'created_at',
                orderable: true, searchable: false,
                render: d => `<span class="text-sm text-on-surface-variant">${d}</span>`
            },
            {
                data: 'status', name: 'status',
                orderable: true, searchable: false,
                render: data => {
                    const map = {
                        delivered: 'bg-emerald-100 text-emerald-800',
                        arrived:   'bg-emerald-100 text-emerald-800',
                        transit:   'bg-sky-100 text-sky-800',
                        preparing: 'bg-amber-100 text-amber-800',
                        cancelled: 'bg-slate-100 text-slate-600',
                    };
                    const cls = map[data] || 'bg-slate-100 text-slate-600';
                    return `<span class="px-3 py-1 rounded-full text-xs font-semibold ${cls}">${data.charAt(0).toUpperCase()+data.slice(1)}</span>`;
                }
            },
            {
                data: 'payment_method', name: 'payment_method',
                orderable: true, searchable: false,
                render: (data, type, row) => {
                    const labels = { cod: 'COD', jazzcash: 'JazzCash', bank_transfer: 'Bank Transfer' };
                    const pCls   = row.payment_status === 'paid'
                        ? 'text-emerald-700'
                        : row.payment_status === 'awaiting_verification'
                            ? 'text-amber-700' : 'text-slate-500';
                    return `<div class="text-xs">
                                <span class="font-semibold text-on-surface">${labels[data] || data}</span><br>
                                <span class="${pCls} capitalize">${(row.payment_status||'').replace('_',' ')}</span>
                            </div>`;
                }
            },
            {
                data: 'total_fmt', name: 'total',
                orderable: true, searchable: false,
                className: 'text-right',
                render: d => `<span class="font-bold text-on-surface text-sm">${d}</span>`
            },
            {
                data: 'actions', name: 'actions',
                orderable: false, searchable: false,
                className: 'text-right',
                render: data => `
                    <a href="${data.showUrl}" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed/20 rounded-full transition-all inline-flex" title="View">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </a>`
            },
        ],
        order: [[2, 'desc']],
        pageLength: 15,
        language: {
            processing: '<div class="text-primary font-bold text-sm py-2">Loading...</div>',
            emptyTable: 'No orders found.',
        },
        drawCallback: function () {
            $('#total-count').text(this.api().page.info().recordsTotal);
        },
    });

    // Status filter buttons
    $('#status-filters').on('click', '.status-btn', function () {
        activeStatus = $(this).data('status');
        $('.status-btn').removeClass('active-filter').addClass('inactive-filter');
        $(this).removeClass('inactive-filter').addClass('active-filter');
        table.ajax.reload();
    });

    // Payment method filter
    $('#filter-payment').on('change', function () {
        activePayment = $(this).val();
        table.ajax.reload();
    });
});
</script>
@endpush
