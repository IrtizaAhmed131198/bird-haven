@extends('layouts.admin')

@section('title', 'Reviews | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<style>
    #reviews-table_wrapper .dataTables_filter,
    #reviews-table_wrapper .dataTables_length,
    #reviews-table_wrapper .dataTables_info,
    #reviews-table_wrapper .dataTables_paginate { display: none !important; }
    #reviews-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(0,0,0,0.06); background: #f8fafc; white-space: nowrap; }
    #reviews-table tbody td { padding: 1rem; border-bottom: 1px solid rgba(0,0,0,0.04); vertical-align: middle; font-size: 0.875rem; }
    #reviews-table tbody tr:hover { background: #f8fafc; }
    #reviews-table tbody tr:last-child td { border-bottom: none; }
    .star-filled { color: #f59e0b; }
    .star-empty  { color: #e2e8f0; }
</style>
@endpush

@section('content')

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="font-headline font-extrabold text-3xl text-on-surface">Reviews</h2>
        <p class="text-on-surface-variant mt-1">Moderate customer reviews before they appear on bird pages.</p>
    </div>
    @if ($pendingCount > 0)
        <span class="px-4 py-2 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-sm font-semibold">
            {{ $pendingCount }} pending approval
        </span>
    @endif
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

{{-- Filter Pills --}}
<div class="flex items-center gap-2 mb-6">
    <button data-filter="" class="filter-btn active px-4 py-2 rounded-full text-sm font-semibold transition-all bg-slate-900 text-white">All</button>
    <button data-filter="pending"  class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Pending</button>
    <button data-filter="approved" class="filter-btn px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container text-on-surface-variant hover:bg-slate-200">Approved</button>

    <div class="ml-auto flex items-center gap-3">
        <span class="text-sm text-on-surface-variant" id="total-count"></span>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
            <input id="search-input" type="text" placeholder="Search reviews…"
                class="pl-10 pr-4 py-2 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm w-64 transition-all" />
        </div>
    </div>
</div>

<div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
    <table id="reviews-table" class="w-full">
        <thead>
            <tr>
                <th>Bird</th>
                <th>Customer</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

{{-- Custom Pagination --}}
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

const table = $('#reviews-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{ route('admin.reviews.data') }}',
        data: d => { d.status = activeFilter; }
    },
    columns: [
        { data: 'bird_name',  name: 'bird.name' },
        { data: 'user_name',  name: 'user.name' },
        { data: 'stars',      name: 'rating', orderable: true, searchable: false },
        { data: 'excerpt',    name: 'body', orderable: false },
        { data: 'status',     name: 'approved', orderable: true, searchable: false },
        { data: 'date_fmt',   name: 'created_at', orderable: true, searchable: false },
        { data: 'actions',    name: 'actions', orderable: false, searchable: false, className: 'text-right' },
    ],
    order: [[5, 'desc']],
    pageLength: 15,
    drawCallback(settings) {
        const api = this.api();
        const info = api.page.info();
        $('#total-count').text(info.recordsFiltered + ' review' + (info.recordsFiltered !== 1 ? 's' : ''));

        // Stars rendering
        $('#reviews-table tbody td:nth-child(3)').each(function () {
            const rating = parseInt($(this).text().trim());
            let html = '';
            for (let i = 1; i <= 5; i++) {
                html += `<span class="material-symbols-outlined text-base ${i <= rating ? 'star-filled' : 'star-empty'}" style="font-variation-settings:'FILL' 1;">star</span>`;
            }
            $(this).html(html);
        });

        // Status pill rendering
        $('#reviews-table tbody td:nth-child(5)').each(function () {
            const status = $(this).text().trim();
            if (status === 'approved') {
                $(this).html('<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">Approved</span>');
            } else {
                $(this).html('<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Pending</span>');
            }
        });

        // Custom pagination
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
            ${disabled ? 'opacity-40 pointer-events-none' : ''}"
            ${disabled ? 'disabled' : ''}>${label}</button>`;

    html += btn('←', currentPage - 1, currentPage === 0, false);
    const start = Math.max(0, currentPage - 2);
    const end   = Math.min(totalPages - 1, currentPage + 2);
    for (let i = start; i <= end; i++) {
        html += btn(i + 1, i, false, i === currentPage);
    }
    html += btn('→', currentPage + 1, currentPage >= totalPages - 1, false);

    $('#custom-paginate').html(html);
    $('#custom-info').text(`Showing ${info.start + 1}–${info.end} of ${info.recordsFiltered}`);
}

// Filter pills
$('.filter-btn').on('click', function () {
    $('.filter-btn').removeClass('active bg-slate-900 text-white').addClass('bg-surface-container text-on-surface-variant');
    $(this).addClass('active bg-slate-900 text-white').removeClass('bg-surface-container text-on-surface-variant');
    activeFilter = $(this).data('filter');
    table.ajax.reload();
});

// Search
let searchTimer;
$('#search-input').on('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => table.search(this.value).draw(), 400);
});

// Delete confirm
function confirmDelete(url) {
    Swal.fire({
        title: 'Delete Review?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
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
