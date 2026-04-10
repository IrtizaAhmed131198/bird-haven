@extends('layouts.admin')

@section('title', 'Shipments | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<style>
    #shipments-table_wrapper .dataTables_filter,
    #shipments-table_wrapper .dataTables_length,
    #shipments-table_wrapper .dataTables_info,
    #shipments-table_wrapper .dataTables_paginate { display: none !important; }
    #shipments-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(0,0,0,0.06); background: #f8fafc; white-space: nowrap; }
    #shipments-table tbody td { padding: 1rem; border-bottom: 1px solid rgba(0,0,0,0.04); vertical-align: middle; font-size: 0.875rem; }
    #shipments-table tbody tr:hover { background: #f8fafc; }
    #shipments-table tbody tr:last-child td { border-bottom: none; }
</style>
@endpush

@section('content')

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="font-headline font-extrabold text-3xl text-on-surface">Shipments</h2>
        <p class="text-on-surface-variant mt-1">Track and manage all active shipments.</p>
    </div>
    <a href="{{ route('admin.shipments.create') }}"
       class="px-5 py-2.5 bg-primary text-white font-bold rounded-DEFAULT shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">add</span> New Shipment
    </a>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
    </div>
@endif

{{-- Filter Pills --}}
<div class="flex flex-wrap items-center gap-2 mb-6">
    <button data-filter="" class="filter-btn active px-4 py-2 rounded-full text-sm font-semibold transition-all bg-slate-900 text-white">All</button>
    <button data-filter="hatchery"  class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Hatchery</button>
    <button data-filter="health"    class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Health</button>
    <button data-filter="in_flight" class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">In Flight</button>
    <button data-filter="local"     class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Local</button>
    <button data-filter="delivered" class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Delivered</button>

    <div class="ml-auto flex items-center gap-3">
        <span class="text-sm text-on-surface-variant" id="total-count"></span>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input id="search-input" type="text" placeholder="Search shipments…"
                class="pl-10 pr-4 py-2 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm w-64 transition-all" />
        </div>
    </div>
</div>

<div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
    <table id="shipments-table" class="w-full">
        <thead>
            <tr>
                <th>Tracking #</th>
                <th>Order</th>
                <th>Customer</th>
                <th>Stage</th>
                <th>Est. Delivery</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="mt-4 flex items-center justify-between">
    <div id="custom-info" class="text-sm text-on-surface-variant"></div>
    <div id="custom-paginate" class="flex items-center gap-1"></div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
const stageColors = {
    hatchery:  'bg-violet-50 text-violet-700',
    health:    'bg-sky-50 text-sky-700',
    in_flight: 'bg-blue-50 text-blue-700',
    local:     'bg-amber-50 text-amber-700',
    delivered: 'bg-emerald-50 text-emerald-700',
};

let activeFilter = '';

const table = $('#shipments-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.shipments.data') }}',
        data: d => { d.stage = activeFilter; }
    },
    columns: [
        { data: 'tracking_number', name: 'tracking_number', className: 'font-mono text-xs' },
        { data: 'order_number',    name: 'order.order_number' },
        { data: 'customer',        name: 'user.name' },
        { data: 'stage_label',     name: 'stage', orderable: true, searchable: false },
        { data: 'est_delivery',    name: 'estimated_delivery', orderable: true, searchable: false },
        { data: 'created_fmt',     name: 'created_at', orderable: true, searchable: false },
        { data: 'actions',         name: 'actions', orderable: false, searchable: false, className: 'text-right' },
    ],
    order: [[5, 'desc']],
    pageLength: 15,
    drawCallback(settings) {
        const api  = this.api();
        const info = api.page.info();
        $('#total-count').text(info.recordsFiltered + ' shipment' + (info.recordsFiltered !== 1 ? 's' : ''));

        // Stage pill rendering
        $('#shipments-table tbody td:nth-child(4)').each(function () {
            const raw   = $(this).text().trim();
            // Find the key from the label
            const entry = Object.entries({
                hatchery: 'Hatchery Preparation', health: 'Health Clearance',
                in_flight: 'In Flight', local: 'Local Sanctuary', delivered: 'Delivered'
            }).find(([, v]) => v === raw);
            const cls = entry ? (stageColors[entry[0]] ?? 'bg-slate-100 text-slate-600') : 'bg-slate-100 text-slate-600';
            $(this).html(`<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${cls}">${raw}</span>`);
        });

        buildPagination(info);
    }
});

function buildPagination(info) {
    const totalPages = info.pages;
    const currentPage = info.page;
    let html = '';
    const btn = (label, page, disabled, active) =>
        `<button onclick="table.page(${page}).draw('page')"
            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all
            ${active ? 'bg-slate-900 text-white' : 'bg-surface-container text-on-surface-variant hover:bg-slate-200'}
            ${disabled ? 'opacity-40 pointer-events-none' : ''}">${label}</button>`;
    html += btn('←', currentPage - 1, currentPage === 0, false);
    for (let i = Math.max(0, currentPage - 2); i <= Math.min(totalPages - 1, currentPage + 2); i++) {
        html += btn(i + 1, i, false, i === currentPage);
    }
    html += btn('→', currentPage + 1, currentPage >= totalPages - 1, false);
    $('#custom-paginate').html(html);
    $('#custom-info').text(`Showing ${info.start + 1}–${info.end} of ${info.recordsFiltered}`);
}

$('.filter-btn').on('click', function () {
    $('.filter-btn').removeClass('active bg-slate-900 text-white').addClass('bg-surface-container text-on-surface-variant');
    $(this).addClass('active bg-slate-900 text-white').removeClass('bg-surface-container text-on-surface-variant');
    activeFilter = $(this).data('filter');
    table.ajax.reload();
});

let searchTimer;
$('#search-input').on('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => table.search(this.value).draw(), 400);
});

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
