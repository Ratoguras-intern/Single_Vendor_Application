import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
window.Turbo = Turbo;

// ---------- Turbo Drive (SPA navigation without full page reload) ----------

Turbo.start();
// Delay before the progress bar appears so fast SPA navigations don't flash it.
Turbo.config.drive.progressBarDelay = 250;

// The preloader must only run on real page loads. On Turbo SPA navigations the
// body is swapped instantly; showing the spinner just flickers and makes the
// backend look like it keeps refreshing.
document.addEventListener('turbo:before-render', () => {
    window.__preloaderSkip = true;
});
document.addEventListener('turbo:render', () => {
    const preloader = document.getElementById('page-preloader');
    if (preloader) {
        preloader.style.display = 'none';
    }
});

// Re-run per-page inline scripts after every Turbo render. Page scripts use
// type="text/turbo-script" so neither the browser nor Turbo executes them
// natively — we control exactly when they run. A WeakSet keyed by element
// dedupes the initial full-load (DOMContentLoaded + possible first turbo:render).
const executedScripts = new WeakSet();

function runPageScripts() {
    document.querySelectorAll('script[type="text/turbo-script"]').forEach((script) => {
        if (executedScripts.has(script)) {
            return;
        }
        executedScripts.add(script);
        try {
            (0, eval)(script.textContent);
        } catch (err) {
            console.error('[admin] failed to run page script:', err);
        }
    });
}

document.addEventListener('DOMContentLoaded', runPageScripts);
document.addEventListener('turbo:render', runPageScripts);

// Session expired mid-navigation: middleware redirects to the login page.
// Full-reload it so the guest layout (its own CSS/bundle) renders correctly
// instead of being Turbo-swapped into the admin shell.
document.addEventListener('turbo:before-fetch-response', (event) => {
    const response = event.detail.fetchResponse.response;
    if (response.redirected && new URL(response.url).pathname === '/login') {
        event.preventDefault();
        window.location.href = response.url;
    }
});

// Sidebar desktop/mobile sync. Registered once per page lifetime so Turbo body
// swaps (which re-run x-init) don't leak resize listeners.
let sidebarResizeBound = false;

window.__initAdminSidebar = () => {
    const store = Alpine.store('sidebar');
    if (!store) {
        return;
    }

    const sync = () => {
        if (window.innerWidth < 1280) {
            store.setMobileOpen(false);
            store.isExpanded = false;
            document.body.classList.remove('overflow-hidden');
        } else {
            store.isMobileOpen = false;
            store.isExpanded = true;
            document.body.classList.remove('overflow-hidden');
        }
    };

    sync();

    if (!sidebarResizeBound) {
        sidebarResizeBound = true;
        window.addEventListener('resize', sync);
    }
};

Alpine.start();
