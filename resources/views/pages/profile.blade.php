@extends('layouts.app')

@section('title', 'Profile Settings | Bird Haven')

@section('content')

    {{-- ===== DESKTOP LAYOUT ===== --}}
    <div class="hidden md:flex max-w-[1440px] mx-auto pt-24">

        <x-account-sidebar />

        {{-- Desktop Main Content --}}
        <main class="ml-64 pt-8 pb-32 px-16 min-h-screen w-full">
            <div class="max-w-4xl mx-auto">
                <header class="mb-12">
                    <h1 class="text-4xl font-headline font-extrabold tracking-tight text-on-surface mb-2">Profile Settings</h1>
                    <p class="text-on-surface-variant max-w-lg">Manage your ethical guardianship profile and account security for Bird Haven.</p>
                </header>

                <div class="space-y-10">

                    {{-- Personal Information --}}
                    <section class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(25,28,29,0.06)]">
                        <div class="flex items-center space-x-3 mb-8">
                            <span class="material-symbols-outlined text-primary">badge</span>
                            <h2 class="text-xl font-headline font-bold">Personal Information</h2>
                        </div>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-outline">Full Name</label>
                                    <input name="name" type="text" value="{{ auth()->user()->name }}"
                                        class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary/20 transition-all shadow-sm outline-none" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-outline">Email Address</label>
                                    <input name="email" type="email" value="{{ auth()->user()->email }}"
                                        class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary/20 transition-all shadow-sm outline-none" />
                                </div>
                                <div class="md:col-span-2 space-y-4">
                                    <label class="text-xs font-bold uppercase tracking-wider text-outline">Profile Avatar</label>
                                    <div class="flex items-center space-x-6">
                                        <img id="desktop-avatar-preview" alt="{{ auth()->user()->name }}"
                                            class="w-20 h-20 rounded-full object-cover shadow-md ring-4 ring-primary-container/30"
                                            src="{{ auth()->user()->avatar_url }}" />
                                        <div>
                                            <label class="px-6 py-2 bg-primary-container/30 text-on-primary-container font-semibold rounded-full hover:bg-primary-container/50 transition-colors text-sm cursor-pointer inline-block">
                                                Change Photo
                                                <input id="desktop-avatar-input" name="avatar" type="file" accept="image/*" class="hidden" />
                                            </label>
                                            <p id="desktop-avatar-name" class="text-xs text-on-surface-variant mt-2 ml-1 hidden"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg hover:-translate-y-0.5 transition-all">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </section>

                    {{-- Account Security --}}
                    <section class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(25,28,29,0.06)]">
                        <div class="flex items-center space-x-3 mb-8">
                            <span class="material-symbols-outlined text-primary">security</span>
                            <h2 class="text-xl font-headline font-bold">Account Security</h2>
                        </div>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-outline">Current Password</label>
                                    <input name="current_password" type="password"
                                        class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary/20 transition-all shadow-sm outline-none" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-outline">New Password</label>
                                    <input name="password" type="password" placeholder="Enter new password"
                                        class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary/20 transition-all shadow-sm outline-none" />
                                </div>
                                <div class="md:col-span-2">
                                    <div id="tfa-card" class="flex items-center justify-between p-4 rounded-xl border-l-4 transition-all duration-300 {{ auth()->user()->two_factor_enabled ? 'bg-green-50 border-green-500' : 'bg-secondary-container/10 border-secondary' }}">
                                        <div class="flex items-center space-x-3">
                                            <span id="tfa-icon" class="material-symbols-outlined transition-colors {{ auth()->user()->two_factor_enabled ? 'text-green-600' : 'text-secondary' }}" style="font-variation-settings:'FILL' 1;">verified_user</span>
                                            <div>
                                                <p class="font-bold text-on-surface text-sm">Two-Factor Authentication</p>
                                                <p id="tfa-status-text" class="text-xs mt-0.5">
                                                    @if(auth()->user()->two_factor_enabled)
                                                        <span class="text-green-600 font-semibold">Enabled</span> — A code will be sent to your email on each login
                                                    @else
                                                        <span class="text-on-surface-variant">Disabled</span> — Enable for extra account security
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <button id="tfa-toggle-btn" type="button"
                                            data-enabled="{{ auth()->user()->two_factor_enabled ? '1' : '0' }}"
                                            data-url="{{ route('2fa.toggle') }}"
                                            data-csrf="{{ csrf_token() }}"
                                            class="px-4 py-1.5 text-xs font-bold rounded-full transition-all {{ auth()->user()->two_factor_enabled ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-primary text-white hover:bg-primary/90' }}">
                                            {{ auth()->user()->two_factor_enabled ? 'Disable' : 'Enable' }}
                                        </button>
                                    </div>
                                    <p id="tfa-toast" class="text-xs mt-2 ml-1 hidden"></p>
                                </div>
                            </div>
                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg hover:-translate-y-0.5 transition-all">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </section>

                    {{-- Shipping Addresses --}}
                    <section class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_20px_40px_rgba(25,28,29,0.06)]">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-3">
                                <span class="material-symbols-outlined text-primary">location_on</span>
                                <h2 class="text-xl font-headline font-bold">Shipping Addresses</h2>
                            </div>
                            <button type="button" onclick="openAddressModal()"
                                class="flex items-center space-x-2 text-primary font-bold text-sm hover:opacity-75 transition-opacity">
                                <span class="material-symbols-outlined text-sm">add</span><span>Add New</span>
                            </button>
                        </div>

                        <div id="address-list">
                            @forelse ($addresses as $address)
                                <div class="address-card p-6 bg-surface-container-low rounded-xl mb-4 border-2 {{ $address->is_default ? 'border-primary/30' : 'border-transparent' }}"
                                     data-id="{{ $address->id }}"
                                     data-label="{{ $address->label }}"
                                     data-name="{{ $address->name }}"
                                     data-address="{{ $address->address }}"
                                     data-address2="{{ $address->address2 }}"
                                     data-city="{{ $address->city }}"
                                     data-state="{{ $address->state }}"
                                     data-postal="{{ $address->postal_code }}"
                                     data-country="{{ $address->country }}"
                                     data-phone="{{ $address->phone }}"
                                     data-default="{{ $address->is_default ? '1' : '0' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            @if($address->is_default)
                                                <span class="text-[10px] text-primary font-bold uppercase tracking-widest mb-1 block">Default Address</span>
                                            @endif
                                            <span class="text-[10px] bg-surface-container px-2 py-0.5 rounded-full text-on-surface-variant font-semibold mb-2 inline-block">{{ $address->label }}</span>
                                            <p class="font-bold text-on-surface">{{ $address->name }}</p>
                                            <p class="text-sm text-on-surface-variant mt-1">{{ $address->address }}{{ $address->address2 ? ', ' . $address->address2 : '' }}</p>
                                            <p class="text-sm text-on-surface-variant">{{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }} {{ $address->postal_code }}</p>
                                            @if($address->phone)
                                                <p class="text-sm text-on-surface-variant">{{ $address->phone }}</p>
                                            @endif
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            @if(! $address->is_default)
                                                <button onclick="setDefaultAddress({{ $address->id }}, this)"
                                                    class="text-xs text-secondary font-semibold hover:underline">Set Default</button>
                                            @endif
                                            <button onclick="openAddressModal(this.closest('.address-card'))"
                                                class="text-primary text-sm font-medium hover:underline">Edit</button>
                                            <button onclick="deleteAddress({{ $address->id }}, this.closest('.address-card'))"
                                                class="text-red-500 text-sm font-medium hover:underline">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p id="no-addresses-msg" class="text-on-surface-variant text-sm">No addresses saved yet. Add your first delivery address.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    {{-- ===== Address Modal ===== --}}
    <div id="address-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeAddressModal()"></div>

        {{-- Panel --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-outline-variant/10">
                <h3 id="modal-title" class="font-headline font-bold text-lg text-on-surface">Add New Address</h3>
                <button onclick="closeAddressModal()" class="p-2 hover:bg-surface-container rounded-full transition-colors">
                    <span class="material-symbols-outlined text-on-surface-variant">close</span>
                </button>
            </div>

            <form id="address-form" class="p-6 space-y-4">
                <input type="hidden" id="addr-id" value="" />

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-outline">Label</label>
                        <select id="addr-label" class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none">
                            <option value="Home">Home</option>
                            <option value="Office">Office</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-outline">Full Name *</label>
                        <input id="addr-name" type="text" placeholder="Recipient name"
                            class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-outline">Street Address *</label>
                    <input id="addr-address" type="text" placeholder="House no. and street"
                        class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-outline">Apartment / Area (optional)</label>
                    <input id="addr-address2" type="text" placeholder="Flat, area, landmark..."
                        class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input type="hidden" name="country" id="addr-country" value="Pakistan" />
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-outline">City *</label>
                        <input id="addr-city" type="text" placeholder="e.g. Karachi"
                            class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-outline">Province</label>
                        <input id="addr-state" type="text" placeholder="e.g. Sindh"
                            class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-outline">Postal Code *</label>
                        <input id="addr-postal" type="text" placeholder="e.g. 75500"
                            class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-outline">Phone</label>
                        <input id="addr-phone" type="tel" placeholder="03XX-XXXXXXX"
                            class="w-full px-4 py-3 bg-surface-container rounded-xl ring-1 ring-outline-variant/20 focus:ring-2 focus:ring-primary/20 outline-none text-sm" />
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input id="addr-default" type="checkbox" class="w-4 h-4 rounded text-primary focus:ring-primary" />
                    <span class="text-sm text-on-surface-variant">Set as default address</span>
                </label>

                <p id="addr-error" class="text-xs text-red-500 hidden"></p>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAddressModal()"
                        class="flex-1 py-3 bg-surface-container text-on-surface font-semibold rounded-full hover:bg-surface-container-high transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="button" id="addr-save-btn" onclick="saveAddress()"
                        class="flex-1 py-3 bg-primary text-white font-bold rounded-full shadow-lg hover:-translate-y-0.5 transition-all text-sm">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <div class="md:hidden pt-20 pb-32 px-6">
        <div class="space-y-6">

            {{-- Personal Info --}}
            <section class="bg-surface-container-lowest rounded-xl p-6 shadow-sm">
                <h2 class="font-headline font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">badge</span>Personal Information
                </h2>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PATCH')
                    <div class="flex items-center gap-4 mb-6">
                        <img id="mobile-avatar-preview" alt="Profile"
                            class="w-16 h-16 rounded-full object-cover shadow-md ring-4 ring-primary-container/30"
                            src="{{ auth()->user()->avatar_url }}" />
                        <div>
                            <label class="px-4 py-2 bg-primary-container/30 text-on-primary-container font-semibold rounded-full text-sm cursor-pointer inline-block">
                                Change Photo
                                <input id="mobile-avatar-input" name="avatar" type="file" accept="image/*" class="hidden" />
                            </label>
                            <p id="mobile-avatar-name" class="text-xs text-on-surface-variant mt-1.5 hidden"></p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-outline block mb-1.5">Full Name</label>
                        <input name="name" type="text" value="{{ auth()->user()->name }}"
                            class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary/20 outline-none" />
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-outline block mb-1.5">Email Address</label>
                        <input name="email" type="email" value="{{ auth()->user()->email }}"
                            class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary/20 outline-none" />
                    </div>
                    <button type="submit" class="w-full py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg">
                        Save Changes
                    </button>
                </form>
            </section>

            {{-- Security --}}
            <section class="bg-surface-container-lowest rounded-xl p-6 shadow-sm">
                <h2 class="font-headline font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">security</span>Account Security
                </h2>
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-outline block mb-1.5">Current Password</label>
                        <input name="current_password" type="password"
                            class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 outline-none" />
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wider text-outline block mb-1.5">New Password</label>
                        <input name="password" type="password" placeholder="Enter new password"
                            class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 outline-none" />
                    </div>
                    <button type="submit" class="w-full py-4 bg-surface-container-high text-on-surface font-bold rounded-full">
                        Update Password
                    </button>
                </form>
            </section>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function bindAvatarPreview(inputId, previewId, nameId) {
    const input   = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const label   = document.getElementById(nameId);
    if (!input) return;

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        // Show filename
        if (label) {
            label.textContent = file.name;
            label.classList.remove('hidden');
        }

        // Show image preview
        const reader = new FileReader();
        reader.onload = e => { if (preview) preview.src = e.target.result; };
        reader.readAsDataURL(file);
    });
}

bindAvatarPreview('desktop-avatar-input', 'desktop-avatar-preview', 'desktop-avatar-name');
bindAvatarPreview('mobile-avatar-input',  'mobile-avatar-preview',  'mobile-avatar-name');

// ── Shipping Addresses ────────────────────────────────────────────────────
const CSRF        = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';
const storeUrl    = '{{ route('addresses.store') }}';
const modal       = document.getElementById('address-modal');
const addrError   = document.getElementById('addr-error');

function openAddressModal(card = null) {
    // Reset form
    document.getElementById('addr-id').value       = '';
    document.getElementById('addr-label').value    = 'Home';
    document.getElementById('addr-name').value     = '';
    document.getElementById('addr-address').value  = '';
    document.getElementById('addr-address2').value = '';
    document.getElementById('addr-country').value     = 'Pakistan';
    document.getElementById('addr-city').value     = '';
    document.getElementById('addr-state').value    = '';
    document.getElementById('addr-postal').value   = '';
    document.getElementById('addr-phone').value    = '';
    document.getElementById('addr-default').checked = false;
    addrError.classList.add('hidden');

    document.getElementById('modal-title').textContent = card ? 'Edit Address' : 'Add New Address';

    // If editing, pre-fill from data attributes
    if (card) {
        document.getElementById('addr-id').value       = card.dataset.id;
        document.getElementById('addr-label').value    = card.dataset.label || 'Home';
        document.getElementById('addr-name').value     = card.dataset.name;
        document.getElementById('addr-address').value  = card.dataset.address;
        document.getElementById('addr-address2').value = card.dataset.address2 || '';
        document.getElementById('addr-country').value     = card.dataset.country || 'Pakistan';
        document.getElementById('addr-city').value     = card.dataset.city;
        document.getElementById('addr-state').value    = card.dataset.state || '';
        document.getElementById('addr-postal').value   = card.dataset.postal;
        document.getElementById('addr-phone').value    = card.dataset.phone || '';
        document.getElementById('addr-default').checked = card.dataset.default === '1';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAddressModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function saveAddress() {
    const id      = document.getElementById('addr-id').value;
    const btn     = document.getElementById('addr-save-btn');
    const payload = {
        label:       document.getElementById('addr-label').value,
        name:        document.getElementById('addr-name').value.trim(),
        address:     document.getElementById('addr-address').value.trim(),
        address2:    document.getElementById('addr-address2').value.trim(),
        country:        document.getElementById('addr-country').value.trim(),
        city:        document.getElementById('addr-city').value.trim(),
        state:       document.getElementById('addr-state').value.trim(),
        postal_code: document.getElementById('addr-postal').value.trim(),
        phone:       document.getElementById('addr-phone').value.trim(),
        is_default:  document.getElementById('addr-default').checked ? 1 : 0,
    };

    // Basic validation
    if (! payload.name || ! payload.address || ! payload.city || ! payload.postal_code) {
        addrError.textContent = 'Please fill in all required fields.';
        addrError.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Saving...';
    addrError.classList.add('hidden');

    try {
        const url    = id ? `/addresses/${id}` : storeUrl;
        const method = id ? 'PATCH' : 'POST';

        const res  = await fetch(url, {
            method,
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (! res.ok) {
            const msgs = data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to save address.';
            addrError.textContent = msgs;
            addrError.classList.remove('hidden');
            return;
        }

        closeAddressModal();
        renderAddressCard(data.address, id);

    } catch (e) {
        addrError.textContent = 'Something went wrong. Please try again.';
        addrError.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Address';
    }
}

async function deleteAddress(id, card) {
    if (! confirm('Remove this address?')) return;

    const res = await fetch(`/addresses/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });

    if (res.ok) {
        card.remove();
        const remaining = document.querySelectorAll('.address-card');
        if (remaining.length === 0) {
            const list = document.getElementById('address-list');
            list.innerHTML = '<p id="no-addresses-msg" class="text-on-surface-variant text-sm">No addresses saved yet. Add your first delivery address.</p>';
        }
    }
}

async function setDefaultAddress(id, btn) {
    const res = await fetch(`/addresses/${id}/default`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });

    if (res.ok) {
        // Update UI: remove default badges and borders from all, then add to this one
        document.querySelectorAll('.address-card').forEach(card => {
            card.classList.remove('border-primary/30');
            card.classList.add('border-transparent');
            card.dataset.default = '0';
            const badge = card.querySelector('.default-badge');
            if (badge) badge.remove();
            // Restore Set Default button if not the current card
            const actionsDiv = card.querySelector('.address-actions');
            if (actionsDiv && card.dataset.id != id) {
                const hasSetDefault = actionsDiv.querySelector('.set-default-btn');
                if (! hasSetDefault) {
                    const newBtn = document.createElement('button');
                    newBtn.className = 'set-default-btn text-xs text-secondary font-semibold hover:underline';
                    newBtn.textContent = 'Set Default';
                    newBtn.onclick = () => setDefaultAddress(card.dataset.id, newBtn);
                    actionsDiv.prepend(newBtn);
                }
            }
        });

        const thisCard = document.querySelector(`.address-card[data-id="${id}"]`);
        if (thisCard) {
            thisCard.classList.remove('border-transparent');
            thisCard.classList.add('border-primary/30');
            thisCard.dataset.default = '1';
            btn.remove(); // Remove "Set Default" button from this card
        }
    }
}

function renderAddressCard(addr, editId) {
    const isDefault = addr.is_default;
    const html = `
        <div class="address-card p-6 bg-surface-container-low rounded-xl mb-4 border-2 ${isDefault ? 'border-primary/30' : 'border-transparent'}"
             data-id="${addr.id}"
             data-label="${addr.label}"
             data-name="${addr.name}"
             data-address="${addr.address}"
             data-address2="${addr.address2 || ''}"
             data-city="${addr.city}"
             data-state="${addr.state || ''}"
             data-postal="${addr.postal_code}"
             data-country="${addr.country || ''}"
             data-phone="${addr.phone || ''}"
             data-default="${isDefault ? '1' : '0'}">
            <div class="flex justify-between items-start">
                <div>
                    ${isDefault ? '<span class="text-[10px] text-primary font-bold uppercase tracking-widest mb-1 block">Default Address</span>' : ''}
                    <span class="text-[10px] bg-surface-container px-2 py-0.5 rounded-full text-on-surface-variant font-semibold mb-2 inline-block">${addr.label}</span>
                    <p class="font-bold text-on-surface">${addr.name}</p>
                    <p class="text-sm text-on-surface-variant mt-1">${addr.address}${addr.address2 ? ', ' + addr.address2 : ''}</p>
                    <p class="text-sm text-on-surface-variant">${addr.city}${addr.state ? ', ' + addr.state : ''} ${addr.postal_code}</p>
                    ${addr.phone ? `<p class="text-sm text-on-surface-variant">${addr.phone}</p>` : ''}
                </div>
                <div class="address-actions flex flex-col items-end gap-2">
                    ${! isDefault ? `<button class="set-default-btn text-xs text-secondary font-semibold hover:underline" onclick="setDefaultAddress(${addr.id}, this)">Set Default</button>` : ''}
                    <button class="text-primary text-sm font-medium hover:underline" onclick="openAddressModal(this.closest('.address-card'))">Edit</button>
                    <button class="text-red-500 text-sm font-medium hover:underline" onclick="deleteAddress(${addr.id}, this.closest('.address-card'))">Remove</button>
                </div>
            </div>
        </div>`;

    const list = document.getElementById('address-list');
    const noMsg = document.getElementById('no-addresses-msg');
    if (noMsg) noMsg.remove();

    if (editId) {
        // Replace existing card
        const existing = document.querySelector(`.address-card[data-id="${editId}"]`);
        if (existing) existing.outerHTML = html;
        // Re-query after replacement
        document.querySelector(`.address-card[data-id="${addr.id}"]`); // trigger reflow
    } else {
        list.insertAdjacentHTML('beforeend', html);
    }

    // If this address is now default, update other cards' borders
    if (isDefault) {
        document.querySelectorAll(`.address-card:not([data-id="${addr.id}"])`).forEach(c => {
            c.classList.remove('border-primary/30');
            c.classList.add('border-transparent');
        });
    }
}

// ── 2FA Toggle ────────────────────────────────────────────────────────────
const tfaBtn  = document.getElementById('tfa-toggle-btn');
const tfaCard = document.getElementById('tfa-card');
const tfaIcon = document.getElementById('tfa-icon');
const tfaText = document.getElementById('tfa-status-text');
const tfaToast = document.getElementById('tfa-toast');

if (tfaBtn) {
    tfaBtn.addEventListener('click', async function () {
        tfaBtn.disabled = true;
        tfaBtn.textContent = '...';

        try {
            const res = await fetch(tfaBtn.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': tfaBtn.dataset.csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            const data = await res.json();
            const enabled = data.enabled;

            // Update card styles
            tfaCard.className = tfaCard.className
                .replace(/bg-green-50|bg-secondary-container\/10|border-green-500|border-secondary/g, '')
                .trim();
            tfaCard.classList.add(
                ...(enabled
                    ? ['bg-green-50', 'border-green-500']
                    : ['bg-secondary-container/10', 'border-secondary'])
            );

            // Update icon colour
            tfaIcon.className = tfaIcon.className
                .replace(/text-green-600|text-secondary/g, '')
                .trim();
            tfaIcon.classList.add(enabled ? 'text-green-600' : 'text-secondary');

            // Update status text
            tfaText.innerHTML = enabled
                ? '<span class="text-green-600 font-semibold">Enabled</span> — A code will be sent to your email on each login'
                : '<span class="text-on-surface-variant">Disabled</span> — Enable for extra account security';

            // Update button
            tfaBtn.textContent = enabled ? 'Disable' : 'Enable';
            tfaBtn.className = tfaBtn.className
                .replace(/bg-red-100|text-red-600|hover:bg-red-200|bg-primary|text-white|hover:bg-primary\/90/g, '')
                .trim();
            tfaBtn.classList.add(
                ...(enabled
                    ? ['bg-red-100', 'text-red-600', 'hover:bg-red-200']
                    : ['bg-primary', 'text-white', 'hover:bg-primary/90'])
            );
            tfaBtn.dataset.enabled = enabled ? '1' : '0';

            // Show toast message
            tfaToast.textContent = data.message;
            tfaToast.className = 'text-xs mt-2 ml-1 ' + (enabled ? 'text-green-600' : 'text-on-surface-variant');
            tfaToast.classList.remove('hidden');
            setTimeout(() => tfaToast.classList.add('hidden'), 4000);

        } catch (e) {
            tfaToast.textContent = 'Something went wrong. Please try again.';
            tfaToast.className = 'text-xs mt-2 ml-1 text-red-500';
            tfaToast.classList.remove('hidden');
        } finally {
            tfaBtn.disabled = false;
        }
    });
}
</script>
@endpush
