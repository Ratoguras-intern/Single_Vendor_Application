import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { initTiptapEditor } from './tiptap-editor';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

Alpine.plugin(collapse);
window.Alpine = Alpine;
window.Turbo = Turbo;

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_APP_HOST,
    wsPort: import.meta.env.VITE_REVERB_APP_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_APP_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_APP_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// ---------- Turbo Drive (SPA navigation without full page reload) ----------

Turbo.start();
// Delay before the progress bar appears so fast SPA navigations don't flash it.
Turbo.config.drive.progressBarDelay = 250;

// Preserve scroll position of the admin content area across Turbo navigations.
// #admin-content-area is a custom scroll container (overflow-y-auto), not
// window — so Turbo's built-in scroll restoration doesn't apply.  We save
// scrollTop *before* Turbo swaps the body and restore it synchronously in
// turbo:render, before any frame can paint at scrollTop=0.
let __adminScrollTop = 0;
let __adminSidebarScrollTop = 0;

function saveAdminScrollPositions() {
    window.__preloaderSkip = true;
    const contentArea = document.getElementById('admin-content-area');
    const sidebarNavigation = document.querySelector('#sidebar [x-ref="navigation"]');

    if (contentArea) __adminScrollTop = contentArea.scrollTop;
    if (sidebarNavigation) __adminSidebarScrollTop = sidebarNavigation.scrollTop;
}

function restoreAdminScrollPositions() {
    const contentArea = document.getElementById('admin-content-area');
    const sidebarNavigation = document.querySelector('#sidebar [x-ref="navigation"]');

    if (contentArea) contentArea.scrollTop = __adminScrollTop;
    if (sidebarNavigation) sidebarNavigation.scrollTop = __adminSidebarScrollTop;
}

document.addEventListener('turbo:before-visit', saveAdminScrollPositions);
document.addEventListener('turbo:before-render', saveAdminScrollPositions);
document.addEventListener('turbo:load', () => {
    requestAnimationFrame(restoreAdminScrollPositions);
});
document.addEventListener('turbo:render', () => {
    // Restore synchronously — the new DOM is already in place when this
    // event fires.  No rAF delay: the browser hasn't painted yet.
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

// Phase 1: Eval scripts to register Alpine.data()/Alpine.store() calls.
// Called before Alpine.start() so data components are available when Alpine
// scans the DOM for the first time.
function registerPageScripts() {
    document.querySelectorAll('script[type="text/turbo-script"]').forEach((script) => {
        if (executedScripts.has(script)) return;
        executedScripts.add(script);
        try {
            (0, eval)(script.textContent);
        } catch (err) {
            console.error('[admin] failed to register page script:', err);
        }
    });
}

// Phase 2: Eval scripts AND initTree sibling elements. Used after Alpine has
// started (DOMContentLoaded / turbo:render) to handle dynamic page content.
function runPageScripts() {
    document.querySelectorAll('script[type="text/turbo-script"]').forEach((script) => {
        if (executedScripts.has(script)) {
            return;
        }
        executedScripts.add(script);
        try {
            (0, eval)(script.textContent);

            // Page-level Alpine components are registered by the script above,
            // after Alpine's initial page scan. Initialise only the component
            // immediately before the script so its x-data expression can run.
            const component = script.previousElementSibling;
            if (component?.hasAttribute('x-data')) {
                Alpine.initTree(component);
            }
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
let sidebarInitialized = false;

window.__initAdminSidebar = () => {
    const store = Alpine.store('sidebar');
    if (!store) {
        return;
    }

    const sync = () => {
        const el = document.getElementById('admin-content-area');
        const top = el ? el.scrollTop : 0;
        if (window.innerWidth < 1280) {
            store.setMobileOpen(false);
            store.isExpanded = false;
        } else {
            store.isMobileOpen = false;
            store.isExpanded = true;
        }
        if (el && top > 0) {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => { el.scrollTop = top; });
            });
        }
    };

    // Only run sync on the very first init — not on every Turbo render.
    // Turbo body swaps re-run x-init, but the Alpine store persists across
    // navigations so resetting sidebar state would disrupt scroll position.
    if (!sidebarInitialized) {
        sidebarInitialized = true;
        sync();
    }

    if (!sidebarResizeBound) {
        sidebarResizeBound = true;
        window.addEventListener('resize', sync);
    }
};

window.__initTiptapEditor = initTiptapEditor;

// Auto-init Tiptap editor when the elements exist (admin pages create/edit).
// Runs on DOMContentLoaded + every turbo:render for SPA navigation.
function autoInitTiptapEditor() {
    const editorEl = document.querySelector('#editor');
    const hiddenEl = document.querySelector('#content-hidden');
    if (editorEl && hiddenEl) {
        // Destroy previous instance if it exists (Turbo SPA navigation swaps DOM)
        if (window.__tiptapEditorInstance) {
            window.__tiptapEditorInstance.destroy();
            window.__tiptapEditorInstance = null;
        }
        window.__tiptapEditorInstance = initTiptapEditor('#content-hidden', '#editor');
    }
}

// Register Alpine.data()/store() from page scripts before Alpine scans the DOM,
// so components like chatAdmin/chatInbox are available on first load.
registerPageScripts();
Alpine.start();

autoInitTiptapEditor();
document.addEventListener('turbo:render', autoInitTiptapEditor);
