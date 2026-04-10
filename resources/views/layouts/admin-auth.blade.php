<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Admin Login | BirdHaven')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "tertiary-fixed": "#99f899", "on-background": "#191c1d",
                        "on-tertiary": "#ffffff", "on-secondary": "#ffffff",
                        "inverse-primary": "#89d0ed", "surface-container": "#eceeef",
                        "on-tertiary-container": "#005f1b", "on-primary-container": "#005870",
                        "outline-variant": "#bfc8cd", "inverse-surface": "#2e3132",
                        "surface-container-lowest": "#ffffff", "primary-container": "#87ceeb",
                        "primary": "#0c6780", "error-container": "#ffdad6",
                        "tertiary": "#016e21", "surface-dim": "#d8dadb",
                        "on-primary": "#ffffff", "on-surface": "#191c1d",
                        "error": "#ba1a1a", "on-error": "#ffffff",
                        "background": "#f8fafb", "surface-container-low": "#f2f4f5",
                        "on-secondary-container": "#526478", "surface-container-high": "#e6e8e9",
                        "on-surface-variant": "#3f484c", "surface-variant": "#e1e3e4",
                        "secondary": "#4e6073", "secondary-container": "#cfe2f9",
                        "tertiary-container": "#7cd97e", "inverse-on-surface": "#eff1f2",
                        "primary-fixed-dim": "#89d0ed", "outline": "#6f787d",
                        "surface-container-highest": "#e1e3e4", "surface-bright": "#f8fafb",
                        "surface": "#f8fafb",
                    },
                    borderRadius: { DEFAULT: "1rem", lg: "2rem", xl: "3rem", full: "9999px" },
                    fontFamily: { headline: ["Manrope"], body: ["Inter"], label: ["Inter"] },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .glass-panel { background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.4); }
        .bg-gradient-sky { background: linear-gradient(135deg, #f8fafb 0%, #e0f2fe 100%); }
        .btn-primary-gradient { background: linear-gradient(135deg, #0c6780 0%, #87ceeb 100%); }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-sky min-h-screen text-on-surface font-body antialiased relative overflow-hidden">

    {{-- Background blobs --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-primary-container/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full bg-tertiary-container/10 blur-[150px]"></div>
    </div>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Access Denied', text: '{{ session('error') }}', confirmButtonColor: '#0c6780' });
        @endif
        @if($errors->any())
            Swal.fire({ icon: 'error', title: 'Login Failed', text: '{{ $errors->first() }}', confirmButtonColor: '#0c6780' });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
