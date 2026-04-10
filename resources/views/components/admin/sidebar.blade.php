<aside class="fixed left-0 top-0 h-screen w-[260px] z-50 bg-slate-900 flex flex-col py-6 overflow-y-auto shadow-2xl shadow-black/20 text-sm antialiased">
    <div class="px-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-sky-400 rounded-lg flex items-center justify-center text-slate-900">
                <span class="material-symbols-outlined text-lg">flutter_dash</span>
            </div>
            <div>
                <h1 class="text-base font-black text-white font-headline">BirdHaven</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Management Console</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 space-y-0.5">
        @php
            $navItems = [
                ['route' => 'admin.dashboard',      'icon' => 'dashboard',      'label' => 'Dashboard'],
                ['route' => 'admin.orders.index',    'icon' => 'shopping_cart',   'label' => 'Orders'],
                ['route' => 'admin.shipments.index', 'icon' => 'local_shipping',  'label' => 'Shipments'],
                ['route' => 'admin.birds.index',    'icon' => 'flutter_dash',   'label' => 'Birds'],
                ['route' => 'admin.categories.index',  'icon' => 'category',      'label' => 'Categories'],
                ['route' => 'admin.accessories.index','icon' => 'storefront',   'label' => 'Accessories'],
                ['route' => 'admin.users.index',    'icon' => 'group',          'label' => 'Users'],
                ['route' => 'admin.contacts.index',    'icon' => 'mark_email_unread', 'label' => 'Messages'],
                ['route' => 'admin.newsletter.index',  'icon' => 'newspaper',         'label' => 'Newsletter'],
                ['route' => 'admin.reviews.index',  'icon' => 'rate_review',    'label' => 'Reviews'],
                ['route' => 'admin.cms.pages.index','icon' => 'description',    'label' => 'CMS Pages'],
                ['route' => 'admin.settings.index', 'icon' => 'settings',       'label' => 'Settings'],
            ];
        @endphp

        @php
            $unreadMessages  = \App\Models\ContactMessage::where('is_read', false)->count();
            $pendingReviews  = \App\Models\Review::where('approved', false)->count();
        @endphp
        @foreach($navItems as $item)
            @php $active = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="relative px-6 py-3 flex items-center gap-3 transition-all duration-200
                      {{ $active
                           ? 'text-white font-semibold bg-white/5 before:absolute before:left-0 before:w-1 before:h-8 before:bg-gradient-to-b before:from-sky-400 before:to-sky-600 before:rounded-r-full'
                           : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                <span class="material-symbols-outlined {{ $active ? 'text-sky-400' : '' }}">{{ $item['icon'] }}</span>
                <span class="flex-1">{{ $item['label'] }}</span>
                @if ($item['route'] === 'admin.contacts.index' && $unreadMessages > 0)
                    <span class="ml-auto text-[10px] font-bold bg-sky-500 text-white px-2 py-0.5 rounded-full">{{ $unreadMessages }}</span>
                @endif
                @if ($item['route'] === 'admin.reviews.index' && $pendingReviews > 0)
                    <span class="ml-auto text-[10px] font-bold bg-amber-500 text-white px-2 py-0.5 rounded-full">{{ $pendingReviews }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="px-6 mt-6">
        <a href="{{ route('admin.birds.create') }}"
           class="w-full py-3 px-4 bg-gradient-to-br from-primary to-primary-container text-white rounded-DEFAULT font-semibold flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            Add New Bird
        </a>
    </div>

    <div class="mt-auto px-6 pt-6 border-t border-slate-800/50">
        <form action="{{ route('admin.logout.admin') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full text-slate-400 hover:text-red-400 px-2 py-3 flex items-center gap-3 transition-colors">
                <span class="material-symbols-outlined">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
