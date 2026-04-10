@extends('layouts.admin')

@section('title', 'Categories | BirdHaven Admin')

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
        <h2 class="font-headline font-extrabold text-4xl text-on-surface tracking-tight">Categories</h2>
        <p class="text-on-surface-variant mt-2">Organise your bird inventory into collections.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-DEFAULT font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Add Category
    </a>
</div>

{{-- Status Filter + Total --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="md:col-span-2 bg-surface-container-low rounded-DEFAULT p-4 flex items-center gap-4">
        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">filter_list</span>
        <div class="flex gap-2 flex-wrap" id="status-filters">
            <button data-status="" class="status-btn active-filter px-3 py-1 rounded-full text-xs font-bold border transition-colors">All</button>
            <button data-status="active" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Active</button>
            <button data-status="inactive" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Inactive</button>
        </div>
    </div>
    <div class="bg-surface-container-low rounded-DEFAULT p-4 flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Categories</span>
        <span id="total-count" class="text-2xl font-headline font-black text-primary">—</span>
    </div>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest/80 backdrop-blur-xl rounded-lg shadow-xl shadow-on-surface/5 overflow-hidden border border-white/40">
    <div class="overflow-x-auto p-4">
        <table id="categories-table" class="w-full text-left">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Slug</th>
                    <th class="text-center">Birds</th>
                    <th>Status</th>
                    <th>Created</th>
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
    let activeStatus = '';

    const table = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.categories.data') }}',
            data: d => { d.status = activeStatus; }
        },
        columns: [
            {
                data: 'image_url',
                name: 'name',
                orderable: true,
                searchable: true,
                render: (data, type, row) => `
                    <div class="flex items-center gap-4">
                        <img src="${data}" alt="${row.name}"
                             class="w-12 h-12 rounded-xl object-cover ring-2 ring-primary-container shrink-0"
                             onerror="this.src='{{ asset('assets/images/default.png') }}'">
                        <div>
                            <div class="font-headline font-bold text-on-surface leading-tight">${row.name}</div>
                            <div class="text-xs text-on-surface-variant mt-0.5 line-clamp-1 max-w-[220px]">${row.description ?? ''}</div>
                        </div>
                    </div>`
            },
            {
                data: 'slug',
                name: 'slug',
                orderable: true,
                searchable: true,
                render: d => `<span class="text-xs font-mono text-on-surface-variant bg-surface-container px-2 py-1 rounded">${d}</span>`
            },
            {
                data: 'birds_count',
                name: 'birds_count',
                orderable: true,
                searchable: false,
                className: 'text-center',
                render: d => `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-fixed text-on-primary-container text-xs font-bold">${d}</span>`
            },
            {
                data: 'status_label',
                name: 'is_active',
                orderable: true,
                searchable: false,
                render: (data, type, row) => {
                    const cls = row.is_active
                        ? 'bg-tertiary-container text-on-tertiary-container'
                        : 'bg-error-container text-on-error-container';
                    const icon = row.is_active ? 'check_circle' : 'cancel';
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest ${cls}">
                                <span class="material-symbols-outlined text-[13px]">${icon}</span>
                                ${data}
                            </span>`;
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                orderable: true,
                searchable: false,
                render: d => `<span class="text-sm text-on-surface-variant">${new Date(d).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'})}</span>`
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-right',
                render: data => `
                    <div class="flex items-center justify-end gap-1">
                        <a href="${data.showUrl}" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed/20 rounded-full transition-all" title="View">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </a>
                        <a href="${data.editUrl}" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed/20 rounded-full transition-all" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>
                        <button onclick="confirmDelete('${data.deleteUrl}')"
                                class="p-2 text-on-surface-variant hover:text-error hover:bg-error-container/50 rounded-full transition-all" title="Delete">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>`
            },
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        language: {
            processing: '<div class="text-primary font-bold text-sm py-2">Loading...</div>',
            emptyTable: 'No categories found.',
        },
        drawCallback: function () {
            $('#total-count').text(this.api().page.info().recordsTotal);
        },
    });

    // Status filter
    $('#status-filters').on('click', '.status-btn', function () {
        activeStatus = $(this).data('status');
        $('.status-btn').removeClass('active-filter').addClass('inactive-filter');
        $(this).removeClass('inactive-filter').addClass('active-filter');
        table.ajax.reload();
    });

    // Delete confirmation
    window.confirmDelete = function (url) {
        Swal.fire({
            title: 'Delete this category?',
            text: 'Categories with birds assigned cannot be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#0c6780',
            confirmButtonText: 'Yes, delete',
        }).then(result => {
            if (!result.isConfirmed) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                           + '<input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        });
    };
});
</script>
@endpush
