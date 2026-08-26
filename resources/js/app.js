import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import collapse from '@alpinejs/collapse';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

Alpine.plugin(collapse);
window.Alpine = Alpine;

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

Livewire.start();
