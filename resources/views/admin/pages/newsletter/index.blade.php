@extends('layouts.admin')

@section('title', 'Newsletter Subscribers | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<style>
    #subs-table_wrapper .dataTables_filter,
    #subs-table_wrapper .dataTables_length,
    #subs-table_wrapper .dataTables_info,
    #subs-table_wrapper .dataTables_paginate { display: none !important; }
    #subs-table thead th { font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#64748b;padding:0.75rem 1rem;border-bottom:1px solid rgba(0,0,0,0.06);background:#f8fafc;white-space:nowrap; }
    #subs-table tbody td { padding:1rem;border-bottom:1px solid rgba(0,0,0,0.04);vertical-align:middle;font-size:0.875rem; }
    #subs-table tbody tr:hover { background:#f8fafc; }
    #subs-table tbody tr:last-child td { border-bottom:none; }
</style>
@endpush

@section('content')

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="font-headline font-extrabold text-3xl text-on-surface">Newsletter Subscribers</h2>
        <p class="text-on-surface-variant mt-1">Manage your mailing list.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="px-5 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
            <p class="text-2xl font-black text-emerald-700">{{ $activeCount }}</p>
            <p class="text-xs font-semibold text-emerald-600">Active</p>
        </div>
        <div class="px-5 py-3 bg-surface-container rounded-xl text-center">
            <p class="text-2xl font-black text-on-surface">{{ $totalCount }}</p>
            <p class="text-xs font-semibold text-on-surface-variant">Total</p>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
    </div>
@endif

{{-- Filter Pills + Search --}}
<div class="flex items-center gap-2 mb-6">
    <button data-filter="" class="filter-btn active px-4 py-2 rounded-full text-sm font-semibold transition-all bg-slate-900 text-white">All</button>
    <button data-filter="active"       class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Active</button>
    <button data-filter="unsubscribed" class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Unsubscribed</button>

    <div class="ml-auto flex items-center gap-3">
        <span class="text-sm text-on-surface-variant" id="total-count"></span>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input id="search-input" type="text" placeholder="Search subscribers…"
                class="pl-10 pr-4 py-2 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm w-64 transition-all" />
        </div>
    </div>
</div>

<div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
    <table id="subs-table" class="w-full">
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Status</th>
                <th>Subscribed</th>
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
let activeFilter = '';

const table = $('#subs-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.newsletter.data') }}',
        data: d => { d.status = activeFilter; }
    },
    columns: [
        { data: 'email',    name: 'email' },
        { data: 'name',     name: 'name', render: v => v ?? '<span class="text-slate-400">—</span>' },
        { data: 'status',   name: 'is_active', orderable: true, searchable: false },
        { data: 'date_fmt', name: 'created_at', orderable: true, searchable: false },
        { data: 'actions',  name: 'actions', orderable: false, searchable: false, className: 'text-right' },
    ],
    order: [[3, 'desc']],
    pageLength: 20,
    drawCallback(settings) {
        const api  = this.api();
        const info = api.page.info();
        $('#total-count').text(info.recordsFiltered + ' subscriber' + (info.recordsFiltered !== 1 ? 's' : ''));

        // Status pill
        $('#subs-table tbody td:nth-child(3)').each(function () {
            const s = $(this).text().trim();
            $(this).html(s === 'active'
                ? '<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">Active</span>'
                : '<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">Unsubscribed</span>'
            );
        });

        buildPagination(info);
    }
});

function buildPagination(info) {
    const tp = info.pages, cp = info.page;
    let html = '';
    const btn = (l, p, d, a) =>
        `<button onclick="table.page(${p}).draw('page')"
            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all
            ${a ? 'bg-slate-900 text-white' : 'bg-surface-container text-on-surface-variant hover:bg-slate-200'}
            ${d ? 'opacity-40 pointer-events-none' : ''}">${l}</button>`;
    html += btn('←', cp - 1, cp === 0, false);
    for (let i = Math.max(0, cp - 2); i <= Math.min(tp - 1, cp + 2); i++) html += btn(i + 1, i, false, i === cp);
    html += btn('→', cp + 1, cp >= tp - 1, false);
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
        title: 'Delete Subscriber?',
        text: 'This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Delete',
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST'; f.action = url;
            f.innerHTML = `<input name="_token" value="{{ csrf_token() }}"><input name="_method" value="DELETE">`;
            document.body.appendChild(f); f.submit();
        }
    });
}
</script>
@endpush
