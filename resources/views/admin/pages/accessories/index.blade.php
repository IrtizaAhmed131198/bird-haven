@extends('layouts.admin')

@section('title', 'Accessories | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
    div.dataTables_wrapper div.dataTables_length select,
    div.dataTables_wrapper div.dataTables_filter input {
        border: 1px solid #bfc8cd; border-radius: .5rem; padding: .3rem .6rem; font-size: .85rem; outline: none;
    }
    div.dataTables_wrapper div.dataTables_filter input:focus { border-color: #0c6780; box-shadow: 0 0 0 2px rgba(12,103,128,.15); }
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter { font-size: .8rem; color: #3f484c; }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button { border-radius: .5rem !important; font-size: .8rem; }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
        background: #0c6780 !important; color: #fff !important; border-color: #0c6780 !important;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
        background: #e6e8e9 !important; color: #0c6780 !important; border-color: transparent !important;
    }
    table.dataTable thead th {
        font-family: 'Manrope', sans-serif; font-weight: 700; font-size: .75rem; color: #3f484c;
        background: rgba(242,244,245,.6); border-bottom: 1px solid rgba(191,200,205,.2);
        padding: 1rem 1.25rem; text-transform: uppercase; letter-spacing: .08em;
    }
    table.dataTable tbody tr { transition: background .15s; }
    table.dataTable tbody tr:hover { background: rgba(186,234,255,.07) !important; }
    table.dataTable tbody td { vertical-align: middle; padding: 1rem 1.25rem; }
    table.dataTable.no-footer { border-bottom: none; }
    .toggle-switch { position:relative; display:inline-flex; align-items:center; cursor:pointer; }
    .toggle-switch input { position:absolute; opacity:0; width:0; height:0; }
    .toggle-track { width:36px; height:20px; background:#e1e3e4; border-radius:9999px; transition:background .2s; position:relative; }
    .toggle-track::after { content:''; position:absolute; top:2px; left:2px; width:16px; height:16px; background:#fff; border-radius:9999px; transition:transform .2s; }
    .toggle-switch input:checked + .toggle-track { background:#0c6780; }
    .toggle-switch input:checked + .toggle-track::after { transform:translateX(16px); }
    .active-filter   { background:rgba(12,103,128,.1); color:#0c6780; border-color:rgba(12,103,128,.2); }
    .inactive-filter { background:white; color:#3f484c; border-color:rgba(191,200,205,.3); }
</style>
@endpush

@section('content')

<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
    <div>
        <h2 class="font-headline font-extrabold text-4xl text-on-surface tracking-tight">Accessories</h2>
        <p class="text-on-surface-variant mt-2">Manage cages, food, toys and all bird care products.</p>
    </div>
    <a href="{{ route('admin.accessories.create') }}"
       class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-DEFAULT font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Add Accessory
    </a>
</div>

{{-- Filters + Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="md:col-span-2 bg-surface-container-low rounded-DEFAULT p-4 flex flex-wrap items-center gap-4">

        {{-- Type filter --}}
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">storefront</span>
            <select id="filter-type"
                class="bg-white border border-outline-variant/30 rounded-lg text-xs font-semibold text-on-surface py-2 px-3 outline-none focus:border-primary transition-colors">
                <option value="">All Types</option>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-px h-5 bg-outline-variant/30 hidden md:block"></div>

        {{-- Status filter --}}
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">radio_button_checked</span>
            <div class="flex gap-2" id="status-filters">
                <button data-status="" class="status-btn active-filter px-3 py-1 rounded-full text-xs font-bold border transition-colors">All</button>
                <button data-status="active" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Active</button>
                <button data-status="inactive" class="status-btn inactive-filter px-3 py-1 rounded-full text-xs font-medium border transition-colors">Inactive</button>
            </div>
        </div>
    </div>
    <div class="bg-surface-container-low rounded-DEFAULT p-4 flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Items</span>
        <span id="total-count" class="text-2xl font-headline font-black text-primary">—</span>
    </div>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest/80 backdrop-blur-xl rounded-lg shadow-xl shadow-on-surface/5 overflow-hidden border border-white/40">
    <div class="overflow-x-auto p-4">
        <table id="accessories-table" class="w-full text-left">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Type</th>
                    <th class="text-right">Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Visible</th>
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

    const table = $('#accessories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.accessories.data') }}',
            data: d => {
                d.type   = $('#filter-type').val();
                d.status = activeStatus;
            }
        },
        columns: [
            {
                data: 'image_url', name: 'name', orderable: true, searchable: true,
                render: (data, type, row) => `
                    <div class="flex items-center gap-4">
                        <img src="${data}" alt="${row.name}"
                             class="w-12 h-12 rounded-xl object-cover ring-2 ring-primary-container shrink-0"
                             onerror="this.src='{{ asset('assets/images/default.png') }}'">
                        <div>
                            <div class="font-headline font-bold text-on-surface leading-tight">${row.name}</div>
                            <div class="text-xs text-on-surface-variant mt-0.5 line-clamp-1 max-w-[200px]">${row.description ?? ''}</div>
                        </div>
                    </div>`
            },
            {
                data: 'type_label', name: 'type', orderable: true, searchable: false,
                render: d => `<span class="px-3 py-1 bg-primary-fixed/30 text-on-primary-container rounded-full text-[10px] font-bold uppercase tracking-wider">${d}</span>`
            },
            {
                data: 'price_fmt', name: 'price', orderable: true, searchable: false, className: 'text-right',
                render: (d, t, row) => {
                    let html = `<span class="text-sm font-bold text-on-surface">${d}</span>`;
                    if (row.original_price) {
                        html += `<br><span class="text-xs text-on-surface-variant line-through">$${parseFloat(row.original_price).toFixed(2)}</span>`;
                    }
                    return html;
                }
            },
            {
                data: 'stock_status', name: 'stock', orderable: true, searchable: false,
                render: data => {
                    const colors = { in:'text-tertiary', low:'text-secondary', out:'text-error' };
                    const dots   = { in:'bg-tertiary', low:'bg-secondary', out:'bg-error' };
                    return `<div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full ${dots[data.level]}"></div>
                                <span class="text-xs font-semibold ${colors[data.level]}">${data.label}</span>
                            </div>`;
                }
            },
            {
                data: 'is_featured', name: 'is_featured', orderable: true, searchable: false,
                render: d => d
                    ? `<span class="inline-flex items-center gap-1 px-2 py-1 bg-primary-fixed/30 text-on-primary-container rounded-full text-[10px] font-bold"><span class="material-symbols-outlined text-[12px]">star</span>Yes</span>`
                    : `<span class="text-xs text-on-surface-variant">—</span>`
            },
            {
                data: 'is_active', name: 'is_active', orderable: true, searchable: false,
                render: (data, type, row) => `
                    <label class="toggle-switch">
                        <input type="checkbox" ${data ? 'checked' : ''}
                               onchange="toggleActive(this, '${row.actions.toggleUrl}')">
                        <div class="toggle-track"></div>
                    </label>`
            },
            {
                data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right',
                render: data => `
                    <div class="flex justify-end gap-1">
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
            emptyTable: 'No accessories found.',
        },
        drawCallback: function () {
            $('#total-count').text(this.api().page.info().recordsTotal);
        },
    });

    $('#filter-type').on('change', () => table.ajax.reload());

    $('#status-filters').on('click', '.status-btn', function () {
        activeStatus = $(this).data('status');
        $('.status-btn').removeClass('active-filter').addClass('inactive-filter');
        $(this).removeClass('inactive-filter').addClass('active-filter');
        table.ajax.reload();
    });

    window.toggleActive = function (checkbox, url) {
        $.ajax({
            url, type: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'PATCH' },
            error: () => {
                checkbox.checked = !checkbox.checked;
                Swal.fire('Error', 'Could not update visibility.', 'error');
            }
        });
    };

    window.confirmDelete = function (url) {
        Swal.fire({
            title: 'Delete this accessory?',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#ba1a1a', cancelButtonColor: '#0c6780',
            confirmButtonText: 'Yes, delete',
        }).then(result => {
            if (!result.isConfirmed) return;
            const form = document.createElement('form');
            form.method = 'POST'; form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                           + '<input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        });
    };
});
</script>
@endpush
