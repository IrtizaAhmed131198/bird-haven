import './bootstrap';

// ── Alpine.js ────────────────────────────────────────────────────────────
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ── SweetAlert2 ──────────────────────────────────────────────────────────
import Swal from 'sweetalert2';
window.Swal = Swal;

// ── Flash messages ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const flash = window.__flashMessages;
    if (!flash) return;

    const config = { confirmButtonColor: '#00658b' };

    if (flash.success) {
        Swal.fire({ icon: 'success', title: 'Success', text: flash.success, ...config, timer: 3000, timerProgressBar: true, showConfirmButton: false });
    } else if (flash.error) {
        Swal.fire({ icon: 'error', title: 'Oops!', text: flash.error, ...config });
    } else if (flash.warning) {
        Swal.fire({ icon: 'warning', title: 'Warning', text: flash.warning, ...config });
    } else if (flash.info) {
        Swal.fire({ icon: 'info', title: 'Notice', text: flash.info, ...config });
    }
});

// ── Global form submit guard ─────────────────────────────────────────────
document.addEventListener('submit', function (e) {
    const form = e.target;
    if (form.dataset.noGuard !== undefined) return;

    const buttons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
    buttons.forEach(function (btn) {
        const originalHTML  = btn.innerHTML;
        const originalValue = btn.value;

        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');

        if (btn.tagName === 'BUTTON') {
            btn.innerHTML =
                '<span class="inline-flex items-center justify-center gap-2">'
                + '<svg class="animate-spin h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">'
                + '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>'
                + '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>'
                + '</svg>'
                + 'Please wait…'
                + '</span>';
        }

        setTimeout(function () {
            btn.disabled = false;
            btn.classList.remove('opacity-60', 'cursor-not-allowed');
            if (btn.tagName === 'BUTTON') btn.innerHTML = originalHTML;
            else btn.value = originalValue;
        }, 15000);
    });
}, true);
