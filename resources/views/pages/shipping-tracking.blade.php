@extends('layouts.app')

@section('title', 'Shipping & Tracking | Bird Haven')

@push('styles')
<style>
    .flight-path-line {
        background: repeating-linear-gradient(to bottom, #00658b 0%, #00658b 50%, transparent 50%, transparent 100%);
        background-size: 2px 12px;
    }
</style>
@endpush

@section('content')

    {{-- ===== DESKTOP MAIN ===== --}}
    <main class="hidden md:block pt-32 pb-20 px-12 max-w-[1440px] mx-auto">

        {{-- Header --}}
        <header class="mb-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span class="uppercase tracking-[0.2em] text-secondary font-bold text-xs mb-4 block">Secure Transit Dashboard</span>
                    <h1 class="text-6xl font-headline font-extrabold tracking-tight text-on-background max-w-2xl leading-[1.1]">
                        Climate-Controlled Journey
                    </h1>
                </div>
                <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-primary">
                    <p class="text-sm text-on-surface-variant mb-1">Tracking Number</p>
                    <p class="text-xl font-headline font-bold text-primary">{{ $shipment->tracking_number ?? 'AV-8829-XQL-2024' }}</p>
                </div>
            </div>
        </header>

        {{-- Status Progress --}}
        <section class="mb-12">
            <div class="bg-surface-container-lowest rounded-xl p-12 shadow-[0_20px_40px_rgba(25,28,29,0.06)] overflow-hidden relative">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <span class="material-symbols-outlined text-9xl">flutter_dash</span>
                </div>
                <div class="grid grid-cols-5 gap-4 relative z-10">
                    @foreach ($trackingSteps as $step)
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-14 h-14 rounded-full {{ $step['active'] ? 'bg-primary text-on-primary shadow-lg shadow-primary/30 animate-pulse' : ($step['done'] ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-high text-on-surface-variant opacity-40') }} flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined">{{ $step['icon'] }}</span>
                            </div>
                            <p class="font-headline font-bold text-sm {{ $step['active'] ? 'text-primary' : 'text-on-surface' }}">{{ $step['label'] }}</p>
                            <p class="text-xs {{ $step['active'] ? 'font-semibold text-primary' : 'text-on-surface-variant' }} mt-1">{{ $step['date'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Telemetry Row --}}
                <div class="mt-12 flex flex-wrap items-center justify-between gap-8 pt-8 border-t border-surface-container">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-tertiary-container/30 flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined">thermostat</span>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant">Cabin Temperature</p>
                            <p class="font-headline font-bold">{{ $shipment->temperature ?? '24.5°C' }} <span class="text-[10px] text-secondary font-medium tracking-wider uppercase ml-1">Stable</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-container/30 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">air</span>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant">Oxygen Levels</p>
                            <p class="font-headline font-bold">{{ $shipment->oxygen ?? '99.2%' }} <span class="text-[10px] text-secondary font-medium tracking-wider uppercase ml-1">Optimal</span></p>
                        </div>
                    </div>
                    <div class="bg-surface-container-low px-6 py-3 rounded-full flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-sm">schedule</span>
                        <span class="text-sm font-semibold">{{ $shipment->estimated_delivery ?? 'Estimated Arrival: In 5-7 Business Days' }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Map + Journey Log --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Map --}}
            <div class="lg:col-span-2 bg-surface-container-low rounded-xl overflow-hidden min-h-[450px] relative shadow-sm flex items-center justify-center">
                @if (file_exists(public_path('images/tracking.png')))
                    <img class="w-full h-full object-cover absolute inset-0" src="{{ asset('assets/images/tracking.png') }}" alt="Tracking map" />
                @else
                    <div class="text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl mb-3 block text-primary">map</span>
                        <p class="font-semibold">Live Map View</p>
                        <p class="text-sm mt-1">{{ $shipment->current_location ?? 'In Transit' }}</p>
                    </div>
                @endif
            </div>

            {{-- Journey Log --}}
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm">
                <h3 class="font-headline font-bold text-lg mb-8">Journey Log</h3>
                <div class="relative pl-6">
                    <div class="flight-path-line absolute left-0 top-0 bottom-0 w-[2px]"></div>
                    @foreach ($journeyLog as $log)
                        <div class="relative mb-8 last:mb-0">
                            <div class="absolute -left-[1.375rem] w-3 h-3 rounded-full {{ $log['done'] ? 'bg-primary' : 'bg-surface-container-highest' }} border-2 border-white"></div>
                            <p class="text-xs text-on-surface-variant mb-1">{{ $log['date'] }}</p>
                            <p class="font-bold text-sm text-on-surface">{{ $log['event'] }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $log['location'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    {{-- ===== MOBILE LAYOUT ===== --}}
    <div class="md:hidden pt-20 pb-32 px-6">

        {{-- Mobile Hero Status --}}
        <section class="mb-8 bg-surface-container-lowest rounded-xl p-6 shadow-[0_20px_40px_rgba(25,28,29,0.06)]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-outline font-semibold">Tracking</p>
                    <p class="font-headline font-bold text-on-surface tracking-tight">{{ $shipment->tracking_number ?? 'BH-7742-SKY-01' }}</p>
                </div>
                <div class="px-3 py-1.5 rounded-full bg-primary-container text-on-primary-container text-xs font-bold">In Flight</div>
            </div>

            {{-- Mobile Progress Steps --}}
            <div class="flex items-center justify-between">
                @foreach ($trackingSteps as $i => $step)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full {{ $step['active'] ? 'bg-primary text-on-primary' : ($step['done'] ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-high text-on-surface-variant opacity-40') }} flex items-center justify-center mb-2">
                            <span class="material-symbols-outlined text-sm">{{ $step['icon'] }}</span>
                        </div>
                        <p class="text-[9px] font-bold text-center text-on-surface-variant leading-tight max-w-[48px]">{{ $step['label'] }}</p>
                    </div>
                    @if (!$loop->last)
                        <div class="flex-1 h-[2px] bg-outline-variant/30 mx-1 mb-4"></div>
                    @endif
                @endforeach
            </div>
        </section>

        {{-- Mobile Telemetry --}}
        <section class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-tertiary bg-tertiary-container/30 p-2 rounded-lg">thermostat</span>
                <div>
                    <p class="text-[10px] text-on-surface-variant">Temperature</p>
                    <p class="font-bold text-sm">{{ $shipment->temperature ?? '24.5°C' }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-primary bg-primary-container/30 p-2 rounded-lg">air</span>
                <div>
                    <p class="text-[10px] text-on-surface-variant">Oxygen</p>
                    <p class="font-bold text-sm">{{ $shipment->oxygen ?? '99.2%' }}</p>
                </div>
            </div>
        </section>

        {{-- Mobile Journey Log --}}
        <section class="bg-surface-container-lowest rounded-xl p-6 shadow-sm">
            <h3 class="font-headline font-bold text-lg mb-6">Journey Log</h3>
            <div class="relative pl-6">
                <div class="flight-path-line absolute left-0 top-0 bottom-0 w-[2px]"></div>
                @foreach ($journeyLog as $log)
                    <div class="relative mb-6 last:mb-0">
                        <div class="absolute -left-[1.375rem] w-3 h-3 rounded-full {{ $log['done'] ? 'bg-primary' : 'bg-surface-container-highest' }} border-2 border-white"></div>
                        <p class="text-xs text-on-surface-variant mb-0.5">{{ $log['date'] }}</p>
                        <p class="font-bold text-sm text-on-surface">{{ $log['event'] }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $log['location'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

@endsection
