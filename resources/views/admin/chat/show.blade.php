@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Chat', 'url' => route('admin.chat.index')],
        ['label' => $conversation->subject, 'url' => null],
    ];
@endphp

@section('content')
<div x-data="chatAdmin({{ $conversation->id }}, {{ $conversation->messages->toJson() }})" class="flex flex-col h-[calc(100vh-10rem)]">

    {{-- Header --}}
    <div class="flex items-center justify-between rounded-t-lg border border-b-0 border-gray-200 bg-gray-50 px-5 py-3 dark:border-gray-700 dark:bg-gray-800/50">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ $conversation->subject }}</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Customer: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $conversation->user->name }}</span>
                &middot; {{ $conversation->user->email }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if ($conversation->status === 'open')
                <form action="{{ route('admin.chat.close', $conversation) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Close Conversation', message: 'Close this chat? Customer can no longer reply.', form: $el })">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Close
                    </button>
                </form>
            @else
                <form action="{{ route('admin.chat.reopen', $conversation) }}" method="POST" x-data @submit.prevent="$el.submit()">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-green-300 bg-white px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50 dark:border-green-700 dark:text-green-400">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 3v5h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Reopen
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.chat.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                Back
            </a>
        </div>
    </div>

    {{-- Messages --}}
    <div class="flex-1 overflow-y-auto border border-gray-200 bg-white px-5 py-4 space-y-4 dark:border-gray-700 dark:bg-gray-900" x-ref="messagesContainer">
        <template x-for="msg in messages" :key="msg.id">
            <div class="flex" :class="msg.sender_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                <div class="max-w-[70%] rounded-xl px-4 py-2.5"
                    :class="msg.sender_id === {{ auth()->id() }}
                        ? 'bg-blue-500 text-white'
                        : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200'">
                    <p class="text-sm" x-text="msg.message"></p>
                    <p class="mt-1 text-[10px] opacity-60" x-text="formatTime(msg.created_at)"></p>
                </div>
            </div>
        </template>
        <div x-ref="bottom"></div>
    </div>

    {{-- Reply --}}
    @if ($conversation->status === 'open')
    <div class="flex items-center gap-2 rounded-b-lg border border-t-0 border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800/50">
        <input type="text" x-model="replyText" @keydown.enter.prevent="sendReply()"
            placeholder="Type your reply..."
            class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        <button @click="sendReply()" :disabled="!replyText.trim() || sending"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Send
        </button>
    </div>
    @else
    <div class="rounded-b-lg border border-t-0 border-gray-200 bg-gray-50 px-5 py-3 text-center dark:border-gray-700 dark:bg-gray-800/50">
        <p class="text-xs text-gray-500 dark:text-gray-400">This conversation is closed.</p>
    </div>
    @endif
</div>

<script type="text/turbo-script">
    Alpine.data('chatAdmin', (conversationId, initialMessages) => ({
        messages: initialMessages || [],
        replyText: '',
        sending: false,
        channel: null,

        async init() {
            this.$nextTick(() => this.scrollToBottom());

            if (window.Echo) {
                this.channel = window.Echo.private('conversation.' + conversationId)
                    .listen('.message.new', (e) => {
                        if (e.sender_id !== {{ auth()->id() }}) {
                            this.messages.push(e);
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    });
            }
        },

        async sendReply() {
            if (!this.replyText.trim() || this.sending) return;
            this.sending = true;
            const text = this.replyText;
            this.replyText = '';

            try {
                const res = await fetch(`/admin/chat/${conversationId}/reply`, {
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
                    this.messages.push(msg);
                    this.$nextTick(() => this.scrollToBottom());
                } else {
                    this.replyText = text;
                }
            } catch (e) {
                this.replyText = text;
            }
            this.sending = false;
        },

        scrollToBottom() {
            if (this.$refs.bottom) {
                this.$refs.bottom.scrollIntoView({ behavior: 'smooth' });
            }
        },

        formatTime(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },
    }));
</script>
@endsection
