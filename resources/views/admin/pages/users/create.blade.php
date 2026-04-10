@extends('layouts.admin')

@section('title', 'Add User | BirdHaven Admin')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="p-2 hover:bg-surface-container rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline font-extrabold text-3xl text-on-surface">Add New User</h2>
            <p class="text-on-surface-variant mt-1">Create a new account for a staff member or customer.</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant/10 p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Avatar Upload --}}
            <div class="flex flex-col items-center gap-4">
                <div class="relative">
                    <img id="avatar-preview"
                         src="https://ui-avatars.com/api/?name=New+User&background=baeaff&color=005870&bold=true&size=128"
                         alt="Avatar Preview"
                         class="h-24 w-24 rounded-full object-cover ring-4 ring-primary-container">
                    <label for="avatar"
                           class="absolute bottom-0 right-0 h-8 w-8 bg-primary text-white rounded-full flex items-center justify-center cursor-pointer shadow-md hover:scale-110 transition-transform"
                           title="Upload photo">
                        <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                    </label>
                </div>
                <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden">
                <p class="text-xs text-on-surface-variant">JPG, PNG or WebP · max 1 MB</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Full Name</label>
                    <input name="name" type="text" value="{{ old('name') }}" placeholder="John Doe"
                        class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Email Address</label>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="john@example.com"
                        class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-on-surface-variant">Password</label>
                <input name="password" type="password" placeholder="Min 8 characters"
                    class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Role</label>
                    <select name="is_admin"
                        class="w-full px-4 py-3 bg-surface-container rounded-DEFAULT border-none ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none appearance-none transition-all">
                        <option value="0" {{ old('is_admin') == '0' ? 'selected' : '' }}>Customer</option>
                        <option value="1" {{ old('is_admin') == '1' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-on-surface-variant">Account Status</label>
                    <label class="flex items-center gap-3 h-[50px] px-4 bg-surface-container rounded-DEFAULT ring-1 ring-outline-variant/20 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 accent-primary">
                        <span class="text-sm font-medium text-on-surface" id="is_active_label">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-outline-variant/10">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-on-surface-variant font-semibold hover:text-on-surface transition-colors">Cancel</a>
                <button type="submit"
                    class="px-8 py-3 bg-primary text-white font-bold rounded-DEFAULT shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Avatar live preview
    document.getElementById('avatar').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
        reader.readAsDataURL(file);
    });

    // Status label toggle
    const cb    = document.getElementById('is_active');
    const label = document.getElementById('is_active_label');
    cb.addEventListener('change', () => { label.textContent = cb.checked ? 'Active' : 'Inactive'; });
</script>
@endpush
