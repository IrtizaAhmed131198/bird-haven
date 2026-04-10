@php
    $navLinks = [
        ['route' => 'home',        'label' => 'Sanctuary'],
        ['route' => 'shop',        'label' => 'Species'],
        ['route' => 'accessories', 'label' => 'Accessories'],
        ['route' => 'about',       'label' => 'Our Story'],
        ['route' => 'contact',     'label' => 'Contact'],
    ];

    $mobileNavLinks = [
        ['route' => 'home',        'label' => 'Home',        'icon' => 'home',        'fill' => true],
        ['route' => 'shop',        'label' => 'Birds',       'icon' => 'flutter_dash','fill' => false],
        ['route' => 'accessories', 'label' => 'Accessories', 'icon' => 'storefront',  'fill' => false],
        ['route' => 'contact',     'label' => 'Contact',     'icon' => 'mail',        'fill' => false],
    ];
@endphp

{{-- Top Navigation Bar --}}
<nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl shadow-[0_20px_40px_rgba(25,28,29,0.06)]">

    {{-- Desktop --}}
    <div class="hidden md:flex items-center justify-between px-12 py-4 max-w-[1440px] mx-auto w-full">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="h-10 w-auto" />
        </a>

        {{-- Nav Links --}}
        <div class="flex items-center space-x-10 font-headline tracking-tight">
            @foreach($navLinks as $link)
                @php $active = request()->routeIs($link['route']) || request()->routeIs($link['route'] . '.*'); @endphp
                <a href="{{ route($link['route']) }}"
                   class="hover:-translate-y-0.5 transition-transform duration-300
                          {{ $active
                              ? 'text-sky-700 font-semibold border-b-2 border-sky-600'
                              : 'text-slate-600 hover:text-slate-900' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Right Actions --}}
        <div class="flex items-center space-x-6">

            {{-- Search --}}
            <div class="relative hidden lg:block">
                <input class="bg-surface-container-low border-none rounded-full px-6 py-2 w-64 text-sm focus:ring-2 focus:ring-primary/20 outline-none"
                    placeholder="Search the archive..." type="text" />
            </div>

            {{-- Auth Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-slate-600 hover:text-primary transition-colors focus:outline-none">
                    <span class="material-symbols-outlined">person</span>
                </button>
                @auth
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-outline-variant/20 py-2 z-50">
                        <div class="px-4 py-2 border-b border-outline-variant/10">
                            <p class="text-xs text-on-surface-variant">Signed in as</p>
                            <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="{{ route('profile') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-base">manage_accounts</span> Profile
                        </a>
                        <a href="{{ route('orders') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-base">receipt_long</span> My Orders
                        </a>
                        <a href="{{ route('wishlist') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-base">favorite</span> Wishlist
                        </a>
                        <div class="border-t border-outline-variant/10 mt-1 pt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-error hover:bg-error-container/30 transition-colors">
                                    <span class="material-symbols-outlined text-base">logout</span> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-3 w-44 bg-white rounded-xl shadow-lg border border-outline-variant/20 py-2 z-50">
                        <a href="{{ route('login') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-base">login</span> Sign In
                        </a>
                        <a href="{{ route('register') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                            <span class="material-symbols-outlined text-base">person_add</span> Create Account
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Cart --}}
            <a class="relative text-slate-600 hover:text-primary transition-colors" href="{{ route('cart') }}">
                <span class="material-symbols-outlined">shopping_bag</span>
                @auth
                    @php $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-primary text-on-primary text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                @endauth
            </a>

        </div>
    </div>

    {{-- Mobile Top Bar --}}
    <div class="flex md:hidden items-center justify-between px-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Bird Haven" class="h-8 w-auto" />
        </a>
        <div class="flex gap-4">
            <a class="text-sky-700" href="{{ auth()->check() ? route('profile') : route('login') }}">
                <span class="material-symbols-outlined">person</span>
            </a>
            <a class="relative text-sky-700" href="{{ route('cart') }}">
                <span class="material-symbols-outlined">shopping_bag</span>
                @auth
                    @php $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-primary text-on-primary text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                @endauth
            </a>
        </div>
    </div>

</nav>

{{-- Mobile Bottom Navigation --}}
<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-6 pb-8 pt-4 bg-white/90 backdrop-blur-xl shadow-[0_-10px_30px_rgba(0,0,0,0.04)] rounded-t-[3rem]">
    @foreach($mobileNavLinks as $link)
        @php $active = request()->routeIs($link['route']) || request()->routeIs($link['route'] . '.*'); @endphp
        <a href="{{ route($link['route']) }}"
           class="flex flex-col items-center justify-center active:scale-95 transition-transform
                  {{ $active ? 'bg-sky-100 text-sky-800 rounded-full px-5 py-2' : 'text-slate-500' }}">
            <span class="material-symbols-outlined"
                  @if($link['fill'] && $active) style="font-variation-settings:'FILL' 1" @endif>
                {{ $link['icon'] }}
            </span>
            <span class="text-[11px] font-medium mt-0.5">{{ $link['label'] }}</span>
        </a>
    @endforeach
</nav>
