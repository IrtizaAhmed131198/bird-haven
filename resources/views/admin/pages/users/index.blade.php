@extends('layouts.admin')

@section('title', 'Users | BirdHaven Admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
    div.dataTables_wrapper div.dataTables_length select,
    div.dataTables_wrapper div.dataTables_filter input {
        border: 1px solid #bfc8cd;
        border-radius: 0.5rem;
        padding: 0.3rem 0.6rem;
        font-size: 0.85rem;
        outline: none;
    }
    div.dataTables_wrapper div.dataTables_filter input:focus {
        border-color: #0c6780;
        box-shadow: 0 0 0 2px rgba(12,103,128,.15);
    }
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter { font-size: 0.8rem; color: #3f484c; }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        font-size: 0.8rem;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
    div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
        background: #0c6780 !important;
        color: #fff !important;
        border-color: #0c6780 !important;
    }
    div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
        background: #e6e8e9 !important;
        color: #0c6780 !important;
        border-color: transparent !important;
    }
    table.dataTable thead th {
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        font-size: 0.8rem;
        color: #3f484c;
        background: rgba(242,244,245,.5);
        border-bottom: 1px solid rgba(191,200,205,.2);
    }
    table.dataTable tbody tr { transition: background 0.15s; }
    table.dataTable tbody tr:hover { background: rgba(186,234,255,.08) !important; }
    table.dataTable tbody td { vertical-align: middle; padding: 0.85rem 1rem; }
    table.dataTable thead th { padding: 1rem; }
    table.dataTable.no-footer { border-bottom: none; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
    <div>
        <h2 class="font-headline font-extrabold text-4xl text-on-surface tracking-tight">Personnel Directory</h2>
        <p class="text-on-surface-variant mt-2">Manage access levels and monitor activity for BirdHaven staff and clients.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-DEFAULT font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
        <span class="material-symbols-outlined text-[20px]">person_add</span>
        Add New User
    </a>
</div>

{{-- Filters + Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    {{-- Role + Status filters --}}
    <div class="md:col-span-2 bg-surface-container-low rounded-DEFAULT p-4 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">manage_accounts</span>
            <div class="flex gap-2 flex-wrap" id="role-filters">
                <button data-role="" class="role-btn active-filter px-3 py-1 rounded-full text-xs font-bold border transition-colors">All Roles</button>
                <button data-role="admin" class="role-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors inactive-filter">Admins</button>
                <button data-role="customer" class="role-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors inactive-filter">Customers</button>
            </div>
        </div>
        <div class="w-px h-5 bg-outline-variant/30 hidden md:block"></div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">radio_button_checked</span>
            <div class="flex gap-2 flex-wrap" id="status-filters">
                <button data-status="" class="status-btn active-filter px-3 py-1 rounded-full text-xs font-bold border transition-colors">All Status</button>
                <button data-status="active" class="status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors inactive-filter">Active</button>
                <button data-status="inactive" class="status-btn px-3 py-1 rounded-full text-xs font-medium border transition-colors inactive-filter">Inactive</button>
            </div>
        </div>
    </div>
    <div class="bg-surface-container-low rounded-DEFAULT p-4 flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Users</span>
        <span id="total-users-count" class="text-2xl font-headline font-black text-primary">—</span>
    </div>
</div>

{{-- Table --}}
<div class="bg-surface-container-lowest/80 backdrop-blur-xl rounded-lg shadow-xl shadow-on-surface/5 overflow-hidden border border-white/40">
    <div class="overflow-x-auto p-4">
        <table id="users-table" class="w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-3">Identity</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Joined</th>
                    <th class="px-4 py-3 text-right">Operations</th>
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
    const ajaxUrl = '{{ route('admin.users.data') }}';
    let activeRole   = '';
    let activeStatus = '';

    const table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: ajaxUrl,
            data: d => {
                d.role   = activeRole;
                d.status = activeStatus;
            }
        },
        columns: [
            {
                data: 'avatar_url',
                name: 'name',
                orderable: true,
                searchable: true,
                render: (data, type, row) => `
                    <div class="flex items-center gap-3">
                        <img src="${data}"
                             alt="${row.initials}"
                             class="h-10 w-10 rounded-full object-cover ring-2 ring-primary-container shrink-0"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="h-10 w-10 rounded-full bg-primary-container items-center justify-center text-primary font-bold text-sm shrink-0 hidden">
                            ${row.initials}
                        </div>
                        <div>
                            <div class="font-headline font-bold text-on-surface leading-tight">${row.name}</div>
                            <div class="text-xs text-on-surface-variant">${row.email}</div>
                        </div>
                    </div>`
            },
            {
                data: 'role_label',
                name: 'is_admin',
                orderable: true,
                searchable: false,
                render: (data, type, row) => {
                    const isAdmin = row.is_admin;
                    const icon    = isAdmin ? 'verified_user' : 'shopping_cart';
                    const cls     = isAdmin
                        ? 'bg-inverse-surface text-white'
                        : 'bg-surface-container-high text-on-surface-variant';
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest ${cls}">
                                <span class="material-symbols-outlined text-[13px]">${icon}</span>
                                ${data}
                            </span>`;
                }
            },
            {
                data: 'status_label',
                name: 'is_active',
                orderable: true,
                searchable: false,
                render: (data, type, row) => {
                    const isActive = row.is_active;
                    const cls = isActive
                        ? 'bg-tertiary-container text-on-tertiary-container'
                        : 'bg-error-container text-on-error-container';
                    const icon = isActive ? 'check_circle' : 'cancel';
                    return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest ${cls}">
                                <span class="material-symbols-outlined text-[13px]">${icon}</span>
                                ${data}
                            </span>`;
                }
            },
            {
                data: 'joined',
                name: 'created_at',
                orderable: true,
                searchable: false,
                render: d => `<span class="text-sm text-on-surface-variant">${d}</span>`
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
        order: [[3, 'desc']],
        pageLength: 15,
        language: {
            processing: '<div class="text-primary font-bold text-sm py-2">Loading...</div>',
            emptyTable: 'No users found.',
        },
        drawCallback: function () {
            $('#total-users-count').text(this.api().page.info().recordsTotal);
        },
    });

    // Shared filter button toggler
    function applyFilterBtns(groupSelector, btnSelector, callback) {
        $(groupSelector).on('click', btnSelector, function () {
            $(groupSelector).find(btnSelector)
                .removeClass('active-filter')
                .addClass('inactive-filter');
            $(this).removeClass('inactive-filter').addClass('active-filter');
            callback($(this).data(btnSelector === '.role-btn' ? 'role' : 'status'));
            table.ajax.reload();
        });
    }

    applyFilterBtns('#role-filters', '.role-btn', val => { activeRole = val; });
    applyFilterBtns('#status-filters', '.status-btn', val => { activeStatus = val; });

    // Delete confirmation
    window.confirmDelete = function (url) {
        Swal.fire({
            title: 'Delete this user?',
            text: 'This action cannot be undone.',
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

<style>
    .active-filter   { background: rgba(12,103,128,.1); color: #0c6780; border-color: rgba(12,103,128,.2); }
    .inactive-filter { background: white; color: #3f484c; border-color: rgba(191,200,205,.3); }
    .inactive-filter:hover { background: rgba(255,255,255,.5); }
</style>
@endpush
