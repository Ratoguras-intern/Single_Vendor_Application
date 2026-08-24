{{-- Announcement Bar --}}
<div x-data="announcementBar()" x-cloak
    class="announcement-bar"
    :class="dismissed ? 'h-0 py-0 overflow-hidden border-0' : ''"
    x-show="!dismissed">
    <div class="section flex items-center justify-center relative">
        <div class="flex items-center justify-center gap-2 min-w-0 px-6 sm:px-8">
            <template x-for="(msg, i) in messages" :key="i">
                <span x-show="active === i"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="flex items-center gap-1.5 text-center leading-snug"
                    x-text="msg"></span>
            </template>
        </div>
        <button x-on:click="dismiss()" class="absolute right-1 sm:right-0 p-2 -m-1 text-white/60 hover:text-white transition-colors" aria-label="Close announcement">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
function announcementBar() {
    return {
        active: 0,
        dismissed: false,
        messages: [
            '\u{1F69A} Free Shipping on Orders Over $100',
            '\u{1F525} Flash Sale \u2013 Up to 50% Off',
            '\u{1F381} New Arrivals Available Now',
            '\u{1F512} 100% Secure Checkout',
        ],
        interval: null,
        init() {
            if (!this.dismissed) {
                this.interval = setInterval(() => {
                    this.active = (this.active + 1) % this.messages.length;
                }, 4000);
            }
        },
        dismiss() {
            this.dismissed = true;
            if (this.interval) clearInterval(this.interval);
            window.dispatchEvent(new Event('announcement:dismissed'));
        }
    }
}
</script>
