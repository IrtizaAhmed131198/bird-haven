{{-- Desktop Footer --}}
<footer class="hidden md:block bg-slate-50 w-full pt-20 pb-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 px-12 max-w-[1440px] mx-auto">
        <div class="col-span-1">
            <div class="mb-6">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Bird Haven" class="h-10 w-auto" />
            </div>
            <p class="text-slate-500 mb-8 max-w-xs leading-relaxed">Dedicated to the preservation and ethical companionship of exotic avian species since 1984.</p>
            <div class="flex gap-4">
                <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all" href="#">
                    <span class="material-symbols-outlined text-xl">share</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all" href="#">
                    <span class="material-symbols-outlined text-xl">mail</span>
                </a>
            </div>
        </div>
        <div>
            <h4 class="font-bold text-slate-900 mb-6">Information</h4>
            <ul class="space-y-4 text-sm">
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="{{ route('privacy') }}">Privacy Policy</a></li>
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="{{ route('ethical.sourcing') }}">Ethical Sourcing</a></li>
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="{{ route('contact') }}">Contact</a></li>
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="{{ route('terms') }}">Terms of Service</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-slate-900 mb-6">The Sanctuary</h4>
            <ul class="space-y-4 text-sm">
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Avian Health Care</a></li>
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Our Hatchery</a></li>
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Training Programs</a></li>
                <li><a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Archive Journal</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-slate-900 mb-6">Join The Archive</h4>
            <p class="text-slate-500 text-sm mb-4 leading-relaxed">Receive curated insights into rare species and care tips.</p>
            @if (session('newsletter_success'))
                <div class="mb-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    {{ session('newsletter_success') }}
                </div>
            @elseif (session('newsletter_info'))
                <div class="mb-3 px-4 py-3 bg-sky-50 border border-sky-200 rounded-xl text-sky-700 text-xs font-semibold">
                    {{ session('newsletter_info') }}
                </div>
            @endif
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                <input name="email" type="email" required placeholder="Your email address"
                    value="{{ old('email') }}"
                    class="bg-white border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none ring-1 ring-slate-200" />
                @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                <button type="submit" class="bg-primary text-white py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">Subscribe</button>
            </form>
        </div>
    </div>
    <div class="mt-20 pt-8 border-t border-slate-200 px-12 max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-slate-400 text-sm">© {{ date('Y') }} Bird Haven. Ethical Avian Guardianship.</p>
        <div class="flex gap-8">
            <a href="{{ route('terms') }}" class="text-slate-400 text-xs hover:text-sky-600 transition-colors">Terms of Service</a>
            <a href="{{ route('privacy') }}" class="text-slate-400 text-xs hover:text-sky-600 transition-colors">Privacy Policy</a>
        </div>
    </div>
</footer>

{{-- Mobile Footer --}}
<footer class="md:hidden bg-slate-50 w-full pt-12 pb-24 px-6 border-t border-slate-100">
    <div class="mb-8">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="Bird Haven" class="h-8 w-auto mb-1" />
        <p class="text-slate-500 text-sm mt-2">© {{ date('Y') }} Bird Haven. Ethical Avian Guardianship.</p>
    </div>
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div class="flex flex-col gap-3">
            <span class="font-bold text-xs uppercase tracking-widest text-slate-400">Legal</span>
            <a class="text-slate-500 text-sm hover:text-sky-600 transition-colors" href="{{ route('privacy') }}">Privacy Policy</a>
            <a class="text-slate-500 text-sm hover:text-sky-600 transition-colors" href="{{ route('ethical.sourcing') }}">Ethical Sourcing</a>
            <a class="text-slate-500 text-sm hover:text-sky-600 transition-colors" href="{{ route('terms') }}">Terms of Service</a>
        </div>
        <div class="flex flex-col gap-3">
            <span class="font-bold text-xs uppercase tracking-widest text-slate-400">Support</span>
            <a class="text-slate-500 text-sm hover:text-sky-600 transition-colors" href="{{ route('contact') }}">Contact</a>
            <a class="text-slate-500 text-sm hover:text-sky-600 transition-colors" href="{{ route('ethical.care') }}">Ethical Care</a>
        </div>
    </div>
</footer>
