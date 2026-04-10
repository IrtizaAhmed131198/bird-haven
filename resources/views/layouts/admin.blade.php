<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'BirdHaven Admin')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "inverse-on-surface": "#eff1f2", "on-surface-variant": "#3f484c",
                        "background": "#f8fafb", "on-tertiary-container": "#005f1b",
                        "surface-container-lowest": "#ffffff", "secondary": "#4e6073",
                        "tertiary-container": "#7cd97e", "surface-tint": "#0c6780",
                        "secondary-container": "#cfe2f9", "on-primary": "#ffffff",
                        "error": "#ba1a1a", "outline-variant": "#bfc8cd", "outline": "#6f787d",
                        "inverse-surface": "#2e3132", "primary": "#0c6780",
                        "on-tertiary": "#ffffff", "surface-container-highest": "#e1e3e4",
                        "inverse-primary": "#89d0ed", "surface-dim": "#d8dadb",
                        "primary-container": "#87ceeb", "surface-container": "#eceeef",
                        "surface-container-high": "#e6e8e9", "on-error-container": "#93000a",
                        "primary-fixed-dim": "#89d0ed", "tertiary-fixed-dim": "#7edb7f",
                        "on-primary-container": "#005870", "on-secondary-container": "#526478",
                        "surface-container-low": "#f2f4f5", "primary-fixed": "#baeaff",
                        "on-error": "#ffffff", "on-background": "#191c1d",
                        "surface-bright": "#f8fafb", "surface-variant": "#e1e3e4",
                        "tertiary": "#016e21", "error-container": "#ffdad6",
                        "on-surface": "#191c1d", "on-secondary": "#ffffff", "surface": "#f8fafb",
                        "secondary-fixed": "#d1e4fb", "tertiary-fixed": "#99f899",
                        "surface-container-lowest": "#ffffff",
                    },
                    borderRadius: { DEFAULT: "1rem", lg: "2rem", xl: "3rem", full: "9999px" },
                    fontFamily: { headline: ["Manrope"], body: ["Inter"], label: ["Inter"] },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafb; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4, h5 { font-family: 'Manrope', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="bg-background font-body text-on-surface">

    <x-admin.sidebar />

    <div class="ml-[260px] min-h-screen flex flex-col">
        <x-admin.navbar />

        <main class="mt-16 p-8 flex-1">
            @yield('content')
        </main>

        <footer class="w-full py-5 border-t border-slate-100 flex justify-between items-center px-8 text-xs tracking-wider uppercase opacity-70">
            <span class="text-slate-400">© {{ date('Y') }} BirdHaven CMS</span>
            <div class="flex gap-8">
                <a class="text-slate-400 hover:text-sky-500 transition-colors" href="#">Documentation</a>
                <a class="text-slate-400 hover:text-sky-500 transition-colors" href="#">Support</a>
                <span class="text-slate-400/50">Version 1.0.0</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Success', text: '{{ session('success') }}', confirmButtonColor: '#0c6780', timer: 3000, timerProgressBar: true, showConfirmButton: false });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}', confirmButtonColor: '#0c6780' });
        @endif
        @if($errors->any())
            Swal.fire({ icon: 'error', title: 'Validation Error', text: '{{ $errors->first() }}', confirmButtonColor: '#0c6780' });
        @endif

        // ── Global form submit guard ─────────────────────────────────────────
        // Disables submit buttons on first click and shows a spinner so the
        // user cannot double-submit while the request is in flight.
        document.addEventListener('submit', function (e) {
            const form = e.target;

            // Skip forms that opt-out (data-no-guard) or are handled by JS (DataTables delete, SweetAlert, etc.)
            if (form.dataset.noGuard !== undefined) return;

            const buttons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            buttons.forEach(function (btn) {
                // Capture original label before mutating
                const originalHTML = btn.innerHTML;
                const originalValue = btn.value;

                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');

                // Show spinner for <button> elements
                if (btn.tagName === 'BUTTON') {
                    btn.innerHTML =
                        '<span class="inline-flex items-center gap-2">'
                        + '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">'
                        + '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>'
                        + '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>'
                        + '</svg>'
                        + 'Processing…'
                        + '</span>';
                }

                // Safety net: re-enable after 15 s in case of network error / back-button
                setTimeout(function () {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    if (btn.tagName === 'BUTTON') btn.innerHTML = originalHTML;
                    else btn.value = originalValue;
                }, 15000);
            });
        }, true);
    </script>
    @stack('scripts')
</body>
</html>
