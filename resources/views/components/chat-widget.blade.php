@auth('web')
<div x-data="chatWidget()" x-init="init()" x-cloak class="fixed bottom-6 right-6 z-[99998]">

    {{-- Floating Button --}}
    <button @click="toggle()" x-show="!open"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-500 text-white shadow-lg transition-all hover:bg-blue-600 hover:scale-105">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white"
            x-show="unreadCount > 0" x-text="unreadCount" x-cloak></span>
    </button>

    {{-- Chat Panel --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed bottom-24 right-6 w-[360px] max-w-[calc(100vw-3rem)] rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900 flex flex-col"
        style="height: 480px; max-height: calc(100vh - 8rem);">

        {{-- Panel Header --}}
        <div class="flex items-center justify-between rounded-t-2xl bg-blue-500 px-5 py-3.5">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Support Chat</h3>
                    <p class="text-[11px] text-blue-100">We typically reply within minutes</p>
                </div>
            </div>
            <button @click="toggle()" class="text-white/70 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        {{-- Conversations List --}}
        <div x-show="view === 'list'" class="flex-1 overflow-y-auto">
            <div class="p-4">
                <button @click="view = 'new'" class="mb-3 w-full rounded-lg bg-blue-50 px-3 py-2.5 text-left text-sm font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400">
                    + Start new conversation
                </button>
                <template x-for="con in conversations" :key="con.id">
                    <button @click="openConversation(con)" class="w-full rounded-lg border border-gray-100 bg-white p-3 text-left hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50 dark:hover:bg-gray-800 mb-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-800 dark:text-white" x-text="con.subject"></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full"
                                :class="con.status === 'open' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'"
                                x-text="con.status"></span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate" x-text="con.latest_message ? con.latest_message.message : 'No messages yet'"></p>
                        <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500" x-text="con.last_message_at ? timeAgo(con.last_message_at) : ''"></p>
                    </button>
                </template>
                <p x-show="conversations.length === 0" class="py-8 text-center text-sm text-gray-400">No conversations yet</p>
            </div>
        </div>

        {{-- New Conversation --}}
        <div x-show="view === 'new'" class="flex-1 flex flex-col">
            <div class="flex items-center gap-2 border-b border-gray-100 px-4 py-2 dark:border-gray-800">
                <button @click="view = 'list'" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">New Conversation</span>
            </div>
            <div class="flex-1 p-4 space-y-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Subject</label>
                    <input type="text" x-model="newSubject" placeholder="How can we help?" maxlength="255"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Message</label>
                    <textarea x-model="newMessage" placeholder="Describe your issue..." rows="4" maxlength="5000"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm resize-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"></textarea>
                </div>
                <button @click="startConversation()" :disabled="!newSubject.trim() || !newMessage.trim() || sending"
                    class="w-full rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!sending">Send Message</span>
                    <span x-show="sending" class="flex items-center justify-center gap-2"><span class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span> Sending...</span>
                </button>
                <p x-show="error" class="text-xs text-red-500 mt-1" x-text="error"></p>
            </div>
        </div>

        {{-- Conversation Messages --}}
        <div x-show="view === 'chat'" class="flex-1 flex flex-col">
            <div class="flex items-center gap-2 border-b border-gray-100 px-4 py-2 dark:border-gray-800">
                <button @click="view = 'list'; activeCon = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="activeCon?.subject"></span>
                    <span class="ml-2 text-[10px] px-2 py-0.5 rounded-full"
                        :class="activeCon?.status === 'open' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500'"
                        x-text="activeCon?.status"></span>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3" x-ref="chatMessages">
                <template x-for="msg in chatMsgs" :key="msg.id">
                    <div class="flex" :class="msg.sender_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[80%] rounded-xl px-3.5 py-2"
                            :class="msg.sender_id === {{ auth()->id() }}
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200'">
                            <p class="text-sm" x-text="msg.message"></p>
                            <p class="mt-0.5 text-[10px] opacity-60" x-text="formatTime(msg.created_at)"></p>
                        </div>
                    </div>
                </template>
                <div x-ref="chatBottom"></div>
            </div>
            <div x-show="activeCon?.status === 'open'" class="flex items-center gap-2 border-t border-gray-100 px-3 py-2.5 dark:border-gray-800">
                <input type="text" x-model="chatText" @keydown.enter.prevent="sendMessage()" placeholder="Type a message..."
                    class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                <button @click="sendMessage()" :disabled="!chatText.trim() || sending"
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500 text-white hover:bg-blue-600 disabled:opacity-50">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <p x-show="error && view === 'chat'" class="px-3 py-1 text-xs text-red-500 border-t border-gray-100 dark:border-gray-800" x-text="error"></p>
            <div x-show="activeCon?.status === 'closed'" class="border-t border-gray-100 px-4 py-3 text-center dark:border-gray-800">
                <p class="text-xs text-gray-400">This conversation is closed.</p>
            </div>
        </div>
    </div>
</div>
@endauth

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('chatWidget', () => ({
        open: false,
        view: 'list',
        conversations: [],
        activeCon: null,
        chatMsgs: [],
        chatText: '',
        newSubject: '',
        newMessage: '',
        sending: false,
        error: null,
        unreadCount: 0,
        channel: null,

        async init() {
            await this.loadConversations();
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.loadConversations();
            }
        },

        async loadConversations() {
            const res = await fetch('/account/chat', { headers: { 'Accept': 'application/json' } });
            if (res.ok) {
                this.conversations = await res.json();
            }
        },

        async openConversation(con) {
            this.activeCon = con;
            this.view = 'chat';

            const res = await fetch(`/account/chat/${con.id}`, { headers: { 'Accept': 'application/json' } });
            if (res.ok) {
                this.chatMsgs = await res.json();
                this.$nextTick(() => this.scrollToBottom());
            }

            if (window.Echo && this.channel) {
                window.Echo.leave('conversation.' + con.id);
            }
            if (window.Echo) {
                this.channel = window.Echo.private('conversation.' + con.id)
                    .listen('.message.new', (e) => {
                        if (e.sender_id !== {{ auth()->id() ?? 0 }}) {
                            this.chatMsgs.push(e);
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    });
            }
        },

        async startConversation() {
            if (this.sending) return;
            this.sending = true;
            this.error = null;
            console.log('[chat] startConversation called', { subject: this.newSubject, message: this.newMessage });
            try {
                const res = await fetch('/account/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ subject: this.newSubject, message: this.newMessage }),
                });
                if (res.ok) {
                    const data = await res.json();
                    console.log('[chat] success', data);
                    this.conversations.unshift(data.conversation);
                    this.newSubject = '';
                    this.newMessage = '';
                    this.openConversation(data.conversation);
                } else {
                    let msg = 'Failed to send. (HTTP ' + res.status + ')';
                    try { const body = await res.json(); msg = body.message || body.error || msg; console.error('[chat] server error', res.status, body); } catch (_) { console.error('[chat] non-json response', res.status); }
                    this.error = msg;
                }
            } catch (e) {
                this.error = 'Network error. Please try again.';
                console.error('[chat]', e);
            }
            this.sending = false;
        },

        async sendMessage() {
            if (!this.chatText.trim() || this.sending || !this.activeCon) return;
            this.sending = true;
            this.error = null;
            const text = this.chatText;
            this.chatText = '';

            try {
                const res = await fetch(`/account/chat/${this.activeCon.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: text }),
                });
                if (res.ok) {
                    const msg = await res.json();
                    this.chatMsgs.push(msg);
                    this.$nextTick(() => this.scrollToBottom());
                } else {
                    this.chatText = text;
                    let msg = 'Failed to send message.';
                    try { const body = await res.json(); msg = body.message || body.error || msg; } catch (_) {}
                    this.error = msg;
                }
            } catch (e) {
                this.chatText = text;
                this.error = 'Network error. Please try again.';
                console.error('[chat]', e);
            }
            this.sending = false;
        },

        scrollToBottom() {
            if (this.$refs.chatBottom) {
                this.$refs.chatBottom.scrollIntoView({ behavior: 'smooth' });
            }
        },

        formatTime(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },

        timeAgo(dateStr) {
            const seconds = Math.floor((new Date() - new Date(dateStr)) / 1000);
            if (seconds < 60) return 'just now';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return minutes + 'm ago';
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + 'h ago';
            const days = Math.floor(hours / 24);
            return days + 'd ago';
        },
    }));
});
</script>
