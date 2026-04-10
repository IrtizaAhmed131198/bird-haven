<aside class="w-64 h-screen fixed left-0 top-0 pt-24 pb-8 bg-white flex flex-col space-y-2 pr-4 z-40 border-r border-outline-variant/10">

    {{-- User info --}}
    <div class="px-8 mb-6">
        <div class="w-16 h-16 rounded-full overflow-hidden mb-4 shadow-lg ring-4 ring-primary-container/30">
            <img alt="{{ auth()->user()->name }}"
                 class="w-full h-full object-cover"
                 src="{{ auth()->user()->avatar_url }}" />
        </div>
        <h3 class="text-slate-900 font-headline font-bold text-base leading-tight">{{ auth()->user()->name }}</h3>
        <p class="text-slate-500 text-xs mt-0.5">Ethical Guardian Member</p>
    </div>

    {{-- Nav links --}}
    <nav class="flex flex-col space-y-1">
        @php
            $links = [
                ['route' => 'profile',           'icon' => 'person',          'label' => 'My Profile'],
                ['route' => 'orders',             'icon' => 'receipt_long',    'label' => 'Order History'],
                ['route' => 'shipping.tracking',  'icon' => 'local_shipping',  'label' => 'Track Orders'],
                ['route' => 'wishlist',           'icon' => 'flutter_dash',    'label' => 'Birds of Interest'],
            ];
        @endphp

        @foreach ($links as $link)
            @php $active = request()->routeIs($link['route']); @endphp
            <a href="{{ route($link['route']) }}"
               class="flex items-center space-x-3 px-8 py-3 text-sm transition-all rounded-r-full
                      {{ $active
                            ? 'bg-sky-50 text-sky-700 font-semibold'
                            : 'text-slate-500 hover:text-sky-700 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-lg"
                      @if($active) style="font-variation-settings:'FILL' 1;" @endif>
                    {{ $link['icon'] }}
                </span>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Bottom --}}
    <div class="mt-auto px-8 pt-6">
        <a href="{{ route('contact') }}"
           class="block w-full py-3 bg-surface-container-high text-on-surface font-semibold rounded-xl hover:bg-primary-container/20 transition-colors text-sm text-center">
            Contact Support
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit"
                class="block w-full py-3 text-red-500 font-semibold text-sm text-center hover:bg-red-50 rounded-xl transition-colors">
                Sign Out
            </button>
        </form>
    </div>

</aside>
