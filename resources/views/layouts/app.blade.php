<!doctype html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Bird Haven | Ethical Avian Guardianship')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}">

    {{-- Google Fonts: preconnect first, then non-render-blocking load --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        onload="this.onload=null;this.rel='stylesheet'" />
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" />
    </noscript>

    {{-- Material Symbols: deferred, display=swap prevents render blocking --}}
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        onload="this.onload=null;this.rel='stylesheet'" />
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" />
    </noscript>

    {{--
        DEV:  run `npm run dev` in a terminal — hot reload, no build needed
        PROD: run `npm run build` + `php artisan optimize`
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Flash message data for app.js --}}
    <script>
        window.__flashMessages = {
            success: @json(session('success')),
            error:   @json(session('error')),
            warning: @json(session('warning')),
            info:    @json(session('info')),
        };
    </script>

    @stack('styles')
</head>
<body class="font-body text-on-surface">

    <x-navbar />

    <main class="pt-16 md:pt-20 pb-32 md:pb-0">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
