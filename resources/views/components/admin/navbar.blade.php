<header class="fixed top-0 right-0 w-[calc(100%-260px)] h-16 z-40 bg-white/80 backdrop-blur-md flex justify-between items-center px-8 shadow-sm shadow-sky-500/5">
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
            <input class="pl-10 pr-4 py-2 bg-slate-50 border-none rounded-full text-sm focus:ring-2 focus:ring-primary/20 w-64 outline-none transition-all"
                   placeholder="Search birds, orders..." type="text" />
        </div>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2 text-slate-500">
            <button class="p-2 hover:bg-sky-50/50 rounded-full transition-colors active:scale-95">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="p-2 hover:bg-sky-50/50 rounded-full transition-colors active:scale-95">
                <span class="material-symbols-outlined">dark_mode</span>
            </button>
            <a href="{{ route('admin.settings.index') }}" class="p-2 hover:bg-sky-50/50 rounded-full transition-colors active:scale-95">
                <span class="material-symbols-outlined">settings</span>
            </a>
        </div>
        <div class="h-8 w-px bg-slate-100"></div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-xs font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Super Admin</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center text-primary font-bold text-sm border-2 border-white shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>
