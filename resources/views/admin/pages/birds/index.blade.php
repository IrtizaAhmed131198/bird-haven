@extends('layouts.admin')

@section('title', 'Birds Inventory | BirdHaven Admin')

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
    .toggle-switch { position:relative; display:inline-flex; align-items:center; cursor:pointer; }
    .toggle-switch input { position:absolute; opacity:0; width:0; height:0; }
    .toggle-track {
        width:36px; height:20px; background:#e1e3e4; border-radius:9999px;
        transition:background .2s; position:relative;
    }
    .toggle-track::after {
        content:''; position:absolute; top:2px; left:2px;
        width:16px; height:16px; background:#fff;
        border-radius:9999px; transition:transform .2s;
    }
    .toggle-switch input:checked + .toggle-track { background:#0c6780; }
    .toggle-switch input:checked + .toggle-track::after { transform:translateX(16px); }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="flex justify-between items-end mb-8">
    <div>
        <nav class="flex text-[10px] font-bold text-slate-400 uppercase tracking-widest gap-2 mb-2">
            <span>Inventory</span>
            <span>/</span>
            <span class="text-primary">Birds</span>
        </nav>
        <h1 class="text-4xl font-extrabold text-on-surface tracking-tight">Birds Inventory</h1>
    </div>
    <a href="{{ route('admin.birds.create') }}"
       class="px-6 py-3.5 bg-primary text-white rounded-xl font-bold flex items-center gap-2 shadow-xl shadow-primary/20 hover:shadow-primary/30 active:scale-95 transition-all">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Add New Bird
    </a>
</div>

<div class="grid grid-cols-12 gap-6">

    {{-- Filter Sidebar --}}
    <aside class="col-span-12 lg:col-span-3 space-y-6">
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-6">
            <h3 class="text-sm font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">filter_list</span>
                Filter Inventory
            </h3>
            <div class="space-y-6">

                {{-- Category --}}
                <div>
                    <label class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-2 block">Category</label>
                    <select id="filter-category"
                        class="w-full bg-surface-container border-none rounded-lg text-sm text-on-surface focus:ring-2 focus:ring-primary/20 py-3 px-4 outline-none">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Stock Status --}}
                <div>
                    <label class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-3 block">Stock Status</label>
                    <div class="space-y-2">
                        @foreach(['' => 'All', 'in_stock' => 'In Stock', 'low_stock' => 'Low Stock', 'out' => 'Out of Stock'] as $val => $label)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="stock_status" value="{{ $val }}"
                                   {{ $val === '' ? 'checked' : '' }}
                                   class="w-4 h-4 accent-primary">
                            <span class="text-sm text-on-surface-variant group-hover:text-on-surface font-medium transition-colors">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Visibility --}}
                <div>
                    <label class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-3 block">Visibility</label>
                    <div class="space-y-2">
                        @foreach(['' => 'All', 'visible' => 'Visible', 'hidden' => 'Hidden'] as $val => $label)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="visibility" value="{{ $val }}"
                                   {{ $val === '' ? 'checked' : '' }}
                                   class="w-4 h-4 accent-primary">
                            <span class="text-sm text-on-surface-variant group-hover:text-on-surface font-medium transition-colors">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button id="reset-filters"
                    class="w-full py-3 bg-surface-container text-on-surface rounded-xl font-bold text-sm hover:bg-surface-container-high active:scale-95 transition-all">
                    Reset Filters
                </button>
            </div>
        </div>
    </aside>

    {{-- DataTable --}}
    <section class="col-span-12 lg:col-span-9">
        <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 overflow-hidden">
            <div class="overflow-x-auto p-4">
                <table id="birds-table" class="w-full text-left">
                    <thead>
                        <tr>
                            <th>Bird &amp; Species</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th class="text-right">Price</th>
                            <th>Stock</th>
                            <th>Visible</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>

</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(function () {
    const ajaxUrl = '{{ route('admin.birds.data') }}';

    const table = $('#birds-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: ajaxUrl,
            data: d => {
                d.category_id  = $('#filter-category').val();
                d.stock_status = $('input[name="stock_status"]:checked').val();
                d.visibility   = $('input[name="visibility"]:checked').val();
            }
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
                             class="w-12 h-12 rounded-full object-cover shadow-sm ring-2 ring-primary-container shrink-0"
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(row.name)}&background=baeaff&color=005870&bold=true&size=128'">
                        <div>
                            <p class="text-sm font-bold text-on-surface leading-tight">${row.name}</p>
                            <p class="text-[11px] italic text-on-surface-variant">${row.species ?? ''}</p>
                        </div>
                    </div>`
            },
            {
                data: 'sku',
                name: 'sku',
                orderable: true,
                searchable: true,
                render: d => d
                    ? `<span class="text-xs font-mono text-on-surface-variant bg-surface-container px-2 py-1 rounded">${d}</span>`
                    : '<span class="text-xs text-outline">—</span>'
            },
            {
                data: 'category_name',
                name: 'category_id',
                orderable: true,
                searchable: false,
                render: d => `<span class="px-3 py-1 bg-primary-fixed/30 text-on-primary-container rounded-full text-[10px] font-bold uppercase tracking-wider">${d}</span>`
            },
            {
                data: 'price_formatted',
                name: 'price',
                orderable: true,
                searchable: false,
                className: 'text-right',
                render: d => `<span class="text-sm font-bold text-on-surface">${d}</span>`
            },
            {
                data: 'stock_status',
                name: 'stock',
                orderable: true,
                searchable: false,
                render: (data) => {
                    const colors = {
                        in:  'text-tertiary',
                        low: 'text-secondary',
                        out: 'text-error',
                    };
                    const dots = {
                        in:  'bg-tertiary',
                        low: 'bg-secondary',
                        out: 'bg-error',
                    };
                    return `<div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full ${dots[data.level]}"></div>
                                <span class="text-xs font-semibold ${colors[data.level]}">${data.label}</span>
                            </div>`;
                }
            },
            {
                data: 'is_active',
                name: 'is_active',
                orderable: true,
                searchable: false,
                render: (data, type, row) => `
                    <label class="toggle-switch" title="Toggle visibility">
                        <input type="checkbox" ${data ? 'checked' : ''}
                               onchange="toggleVisibility(this, '${row.actions.toggleUrl}')">
                        <div class="toggle-track"></div>
                    </label>`
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-right',
                render: data => `
                    <div class="flex justify-end gap-1">
                        <a href="${data.showUrl}" class="p-1.5 hover:bg-primary-fixed/20 rounded-lg text-on-surface-variant hover:text-primary transition-all" title="View">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </a>
                        <a href="${data.editUrl}" class="p-1.5 hover:bg-primary-fixed/20 rounded-lg text-on-surface-variant hover:text-primary transition-all" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>
                        <button onclick="confirmDelete('${data.deleteUrl}')"
                                class="p-1.5 hover:bg-error-container/50 rounded-lg text-on-surface-variant hover:text-error transition-all" title="Delete">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>`
            },
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        language: {
            processing: '<div class="text-primary font-bold text-sm py-2">Loading...</div>',
            emptyTable: 'No birds found in inventory.',
        },
    });

    // Reload on filter change
    $('#filter-category, input[name="stock_status"], input[name="visibility"]')
        .on('change', () => table.ajax.reload());

    // Reset filters
    $('#reset-filters').on('click', function () {
        $('#filter-category').val('');
        $('input[name="stock_status"][value=""]').prop('checked', true);
        $('input[name="visibility"][value=""]').prop('checked', true);
        table.ajax.reload();
    });

    // Toggle visibility via AJAX
    window.toggleVisibility = function (checkbox, url) {
        $.ajax({
            url,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'PATCH' },
            error: () => {
                checkbox.checked = !checkbox.checked;
                Swal.fire('Error', 'Could not update visibility.', 'error');
            }
        });
    };

    // Delete confirmation
    window.confirmDelete = function (url) {
        Swal.fire({
            title: 'Remove this bird?',
            text: 'This will permanently delete the bird and its image.',
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
