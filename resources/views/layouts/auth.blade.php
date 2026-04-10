<!doctype html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Bird Haven')</title>
    <link rel="icon" type="image/ico" href="{{ asset('assets/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-primary": "#ffffff", "secondary": "#4d653a", "surface-tint": "#00658b",
                        "on-tertiary-fixed": "#002021", "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#005778", "tertiary-container": "#9bcdcf",
                        "primary-fixed": "#c4e7ff", "on-tertiary-fixed-variant": "#1a4e50",
                        "on-secondary-fixed": "#0c2000", "on-error-container": "#93000a",
                        "on-surface": "#191c1d", "outline": "#6f787d",
                        "on-secondary-fixed-variant": "#364d24", "surface-dim": "#d9dadb",
                        "secondary-container": "#cfecb4", "on-primary-fixed-variant": "#004c69",
                        "surface-bright": "#f8f9fa", "on-tertiary-container": "#27585a",
                        "outline-variant": "#bfc8cd", "error": "#ba1a1a", "surface": "#f8f9fa",
                        "secondary-fixed": "#cfecb4", "inverse-primary": "#7cd0ff",
                        "tertiary-fixed": "#b9ecee", "inverse-on-surface": "#f0f1f2",
                        "on-primary-fixed": "#001e2c", "secondary-fixed-dim": "#b3cf9a",
                        "tertiary": "#356668", "primary": "#00658b", "inverse-surface": "#2e3132",
                        "primary-fixed-dim": "#7cd0ff", "primary-container": "#77cefe",
                        "surface-container-highest": "#e1e3e4", "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#9ecfd1", "on-error": "#ffffff",
                        "surface-container-high": "#e7e8e9", "on-surface-variant": "#3f484c",
                        "on-tertiary": "#ffffff", "error-container": "#ffdad6",
                        "background": "#f8f9fa", "on-background": "#191c1d",
                        "surface-variant": "#e1e3e4", "surface-container": "#edeeef",
                        "surface-container-low": "#f3f4f5", "on-secondary-container": "#536b3f",
                    },
                    fontFamily: { headline: ["Manrope"], body: ["Plus Jakarta Sans"], label: ["Plus Jakarta Sans"] },
                    borderRadius: { DEFAULT: "1rem", lg: "2rem", xl: "3rem", full: "9999px" },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; -webkit-font-smoothing: antialiased; -webkit-tap-highlight-color: transparent; }
        h1, h2, h3, h4, h5 { font-family: 'Manrope', sans-serif; }
        .bg-gradient-primary { background: linear-gradient(135deg, #00658b 0%, #77cefe 100%); }
    </style>
    @stack('styles')
</head>
<body class="bg-surface font-body text-on-surface min-h-screen">
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#00658b',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#00658b',
            });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#00658b',
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
