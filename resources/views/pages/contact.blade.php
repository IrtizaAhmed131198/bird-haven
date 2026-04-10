@extends('layouts.app')

@section('title', 'Contact | Bird Haven')

@section('content')

    {{-- ===== HERO ===== --}}
    {{-- Desktop --}}
    <header class="hidden md:block mb-20 text-center px-12 max-w-[1440px] mx-auto pt-12">
        <span class="text-sm font-bold uppercase tracking-[0.2em] text-primary mb-4 block">Connect With Us</span>
        <h1 class="text-6xl font-extrabold font-headline text-on-surface tracking-tight leading-[1.1] mb-6">Let's start a <br/>conversation.</h1>
        <p class="text-on-surface-variant max-w-xl mx-auto text-lg leading-relaxed">Whether you are an aspiring guardian or a long-time avian enthusiast, we are here to provide expert guidance and ethical support.</p>
    </header>

    {{-- Mobile Hero --}}
    <header class="md:hidden mb-10 text-center px-6 pt-4">
        <h1 class="text-4xl font-extrabold tracking-tight text-on-surface mb-2">Get in Touch</h1>
        <p class="text-on-surface-variant max-w-[280px] mx-auto leading-relaxed">Our avian guardians are ready to assist you with any questions.</p>
    </header>

    {{-- ===== MOBILE: Quick Contact Buttons ===== --}}
    <div class="md:hidden flex gap-4 mb-10 px-6">
        <a class="flex-1 bg-surface-container-low rounded-xl p-4 flex flex-col items-center justify-center gap-2 active:scale-95 transition-transform" href="tel:+15558292732">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">call</span>
            </div>
            <span class="text-sm font-semibold text-on-surface">Call Us</span>
        </a>
        <a class="flex-1 bg-secondary-container/30 rounded-xl p-4 flex flex-col items-center justify-center gap-2 active:scale-95 transition-transform" href="#">
            <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat_bubble</span>
            </div>
            <span class="text-sm font-semibold text-on-secondary-container">WhatsApp</span>
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
    <div class="hidden md:flex max-w-[1440px] mx-auto px-12 mb-6">
        <div class="w-full p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if ($errors->any())
    <div class="hidden md:flex max-w-[1440px] mx-auto px-12 mb-6">
        <div class="w-full p-4 bg-red-50 border border-red-200 rounded-xl">
            @foreach ($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== DESKTOP: Bento Grid (Info + Form) ===== --}}
    <div class="hidden md:grid grid-cols-12 gap-8 items-start px-12 max-w-[1440px] mx-auto">
        {{-- Left Column --}}
        <div class="col-span-12 lg:col-span-4 space-y-8">
            <div class="bg-surface-container-low rounded-xl p-8 space-y-8">
                <h3 class="text-xl font-bold font-headline mb-4">Direct Channels</h3>
                @foreach ($contactChannels as $channel)
                    <div class="flex items-center gap-5 group cursor-pointer">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm group-hover:bg-primary-container transition-colors duration-300">
                            <span class="material-symbols-outlined">{{ $channel['icon'] }}</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-primary uppercase tracking-widest">{{ $channel['label'] }}</p>
                            <p class="text-lg font-medium">{{ $channel['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-secondary-container/30 rounded-xl p-8">
                <h3 class="text-xl font-bold font-headline mb-6 text-on-secondary-container">Consultation Hours</h3>
                <div class="space-y-4 text-on-secondary-container/80">
                    @foreach ($hours as $hour)
                        <div class="flex justify-between border-b border-secondary-container/20 pb-2">
                            <span>{{ $hour['day'] }}</span>
                            <span class="font-bold">{{ $hour['time'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Column: Contact Form --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-xl p-12 shadow-[0_20px_40px_rgba(25,28,29,0.04)]">
                <form class="grid grid-cols-2 gap-8" action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-on-surface-variant mb-3 px-1">First Name</label>
                        <input class="w-full bg-surface-container-low border-none rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-outline-variant outline-none" name="first_name" placeholder="Elias" type="text" />
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-on-surface-variant mb-3 px-1">Last Name</label>
                        <input class="w-full bg-surface-container-low border-none rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-outline-variant outline-none" name="last_name" placeholder="Vogel" type="text" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-on-surface-variant mb-3 px-1">Email Address</label>
                        <input class="w-full bg-surface-container-low border-none rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-outline-variant outline-none" name="email" placeholder="elias@domain.com" type="email" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-on-surface-variant mb-3 px-1">How can we assist you?</label>
                        <select class="w-auto min-w-[240px] bg-surface-container-low border-none rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 transition-all text-on-surface-variant outline-none" name="topic">
                            <option value="">Select a Topic</option>
                            <option>General Inquiry</option>
                            <option>Bird Care Consultation</option>
                            <option>Ethical Sourcing Question</option>
                            <option>Product Feedback</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-on-surface-variant mb-3 px-1">Message</label>
                        <textarea class="w-full bg-surface-container-low border-none rounded-md px-6 py-4 focus:ring-2 focus:ring-primary/20 transition-all placeholder:text-outline-variant resize-none outline-none" name="message" placeholder="Share your avian journey with us..." rows="6"></textarea>
                    </div>
                    <div class="col-span-2 flex justify-end mt-4">
                        <button class="bg-gradient-to-br from-primary to-primary-container text-white font-bold px-12 py-5 rounded-full shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all duration-300" type="submit">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Mobile Flash --}}
    @if (session('success'))
    <div class="md:hidden mx-6 mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-emerald-600 text-lg">check_circle</span>
        <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
    </div>
    @endif

    {{-- ===== MOBILE: Contact Form ===== --}}
    <section class="md:hidden bg-surface-container-lowest rounded-lg mx-6 p-6 shadow-[0_20px_40px_rgba(25,28,29,0.04)] mb-10">
        <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2 ml-1">Full Name</label>
                <input class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/20 placeholder:text-outline-variant transition-all outline-none" name="full_name" placeholder="John Doe" type="text" />
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2 ml-1">Email Address</label>
                <input class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/20 placeholder:text-outline-variant transition-all outline-none" name="email" placeholder="hello@aviary.com" type="email" />
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2 ml-1">Your Message</label>
                <textarea class="w-full bg-surface-container-low border-none rounded-lg p-4 focus:ring-2 focus:ring-primary/20 placeholder:text-outline-variant transition-all outline-none" name="message" placeholder="How can we help your feathered friend?" rows="4"></textarea>
            </div>
            <button class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary font-bold py-4 rounded-full shadow-lg shadow-primary/20 active:scale-[0.98] transition-all" type="submit">
                Send Inquiry
            </button>
        </form>
    </section>

    {{-- ===== DESKTOP: Location Section ===== --}}
    <section class="hidden md:block mt-20 px-12 max-w-[1440px] mx-auto mb-20">
        <div class="grid grid-cols-12 gap-8 items-stretch">
            <div class="col-span-12 md:col-span-4 bg-tertiary-container/20 rounded-xl p-10 flex flex-col justify-center">
                <h2 class="text-3xl font-headline font-extrabold mb-6 tracking-tight">Visit the Sanctuary</h2>
                <p class="text-on-surface-variant mb-8 leading-relaxed">Our flagship boutique and sanctuary is nestled in the heart of the coastal valley.</p>
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-primary mt-1">location_on</span>
                    <address class="not-italic font-medium">{{ $address ?? '' }}</address>
                </div>
                <button class="mt-10 self-start text-primary font-bold flex items-center gap-2 group">
                    Get Directions
                    <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </button>
            </div>
            <div class="col-span-12 md:col-span-8 relative overflow-hidden rounded-xl min-h-[400px] bg-surface-container-high flex items-center justify-center">
                <div class="text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl mb-4 block text-primary">map</span>
                    <p class="font-semibold">Map View</p>
                    <p class="text-sm mt-1">{{ $address ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MOBILE: Map Section ===== --}}
    <section class="md:hidden px-6 mb-12 space-y-4">
        <h2 class="text-xl font-bold px-1">Visit The Sanctuary</h2>
        <div class="relative w-full h-48 rounded-lg overflow-hidden bg-surface-container-high flex items-center justify-center">
            <div class="text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
                <p class="text-[10px] font-bold mt-2 text-on-surface-variant uppercase tracking-tighter">122 Sanctuary Road, Aviary Park</p>
            </div>
        </div>
    </section>

@endsection
