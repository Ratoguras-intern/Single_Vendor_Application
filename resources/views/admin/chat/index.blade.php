@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Chat', 'url' => null],
    ];
@endphp

@section('content')
<div x-data="chatInbox()" class="flex h-[calc(100vh-10rem)] overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Left Panel: User List --}}
    <div class="w-80 shrink-0 border-r border-gray-200 dark:border-gray-800 flex flex-col">
        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
            <h2 class="text-base font-bold text-gray-800 dark:text-white">Conversations</h2>
            <div class="mt-2 flex gap-1">
                <a href="{{ route('admin.chat.index') }}"
                    class="rounded-md px-2.5 py-1 text-xs font-medium
                    {{ !request('status') && !request('unassigned') ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Open
                    @if ($openCount > 0)
                        <span class="ml-1 inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-blue-100 px-1 text-[10px] font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">{{ $openCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.chat.index', ['status' => 'closed']) }}"
                    class="rounded-md px-2.5 py-1 text-xs font-medium
                    {{ request('status') === 'closed' ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Closed
                </a>
                <a href="{{ route('admin.chat.index', ['unassigned' => 1]) }}"
                    class="rounded-md px-2.5 py-1 text-xs font-medium
                    {{ request('unassigned') ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    Unassigned
                    @if ($unassignedCount > 0)
                        <span class="ml-1 inline-flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-orange-100 px-1 text-[10px] font-bold text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">{{ $unassignedCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($users as $userConvo)
                <button @click="selectUser({{ $userConvo->user_id }}, {{ $userConvo->id }})"
                    class="w-full border-b border-gray-100 px-4 py-3 text-left transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50"
                    :class="selectedUserId === {{ $userConvo->user_id }} ? 'bg-blue-50 dark:bg-blue-900/10' : ''">
                    <div class="flex items-start gap-3">
                        <div class="relative shrink-0">
                            @if($userConvo->user->avatar_path)
                                <img src="{{ asset('storage/' . $userConvo->user->avatar_path) }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ strtoupper(substr($userConvo->user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            @if(is_null($userConvo->admin_id))
                                <span class="absolute -top-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-orange-500 dark:border-gray-900"></span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $userConvo->user->name ?? 'Unknown' }}</span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $userConvo->last_msg?->diffForHumans() }}</span>
                            </div>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 truncate">
                                @if ($userConvo->latestMessage)
                                    @if ($userConvo->latestMessage->sender?->id === auth()->id())
                                        <span class="text-gray-400">You:</span>
                                    @endif
                                    {{ Str::limit($userConvo->latestMessage->message, 60) }}
                                @else
                                    No messages yet
                                @endif
                            </p>
                            <div class="mt-1 flex items-center gap-1.5">
                                <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[9px] font-medium
                                    {{ $userConvo->status === 'open'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                        : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ ucfirst($userConvo->status) }}
                                </span>
                                @if(is_null($userConvo->admin_id))
                                    <span class="inline-flex items-center rounded-full bg-orange-100 px-1.5 py-0.5 text-[9px] font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">Unassigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <p class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">No conversations</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right Panel: Messages --}}
    <div class="flex flex-1 flex-col">
        {{-- Placeholder --}}
        <div x-show="!selectedUserId" class="flex flex-1 items-center justify-center">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">Select a conversation to view messages</p>
            </div>
        </div>

        {{-- Chat View --}}
        <template x-if="selectedUserId">
            <div class="flex flex-1 flex-col">
                {{-- Chat Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3 dark:border-gray-800">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white" x-text="selectedUserName"></h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500" x-text="selectedUserEmail"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="'/admin/chat/' + selectedConvoId" title="Open Full Chat"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Full View
                        </a>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3" x-ref="chatMessages">
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
                    <div x-ref="chatBottom"></div>
                </div>

                {{-- Reply --}}
                <div class="flex items-center gap-2 border-t border-gray-200 px-4 py-3 dark:border-gray-800">
                    <input type="text" x-model="replyText" @keydown.enter.prevent="sendReply()" placeholder="Type your reply..."
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <button @click="sendReply()" :disabled="!replyText.trim() || sending"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 text-white hover:bg-blue-600 disabled:opacity-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<script type="text/turbo-script">
    Alpine.data('chatInbox', () => ({
        selectedUserId: null,
        selectedConvoId: null,
        selectedUserName: '',
        selectedUserEmail: '',
        messages: [],
        replyText: '',
        sending: false,
        channel: null,

        async selectUser(userId, convoId) {
            this.selectedUserId = userId;
            this.selectedConvoId = convoId;
            this.messages = [];
            this.replyText = '';

            const btn = event.currentTarget;
            this.selectedUserName = btn.querySelector('.text-sm.font-semibold')?.textContent || '';
            this.selectedUserEmail = btn.querySelector('.text-xs.text-gray-500')?.textContent || '';

            await this.loadMessages(convoId);
            this.$nextTick(() => this.scrollToBottom());

            if (this.channel) {
                window.Echo.leave('private-conversation.' + this.selectedConvoId);
            }
            if (window.Echo) {
                this.channel = window.Echo.private('conversation.' + convoId)
                    .listen('.message.new', (e) => {
                        if (e.sender_id !== {{ auth()->id() }}) {
                            this.messages.push(e);
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    });
            }
        },

        async loadMessages(convoId) {
            const res = await fetch(`/admin/chat/${convoId}/messages`);
            if (res.ok) {
                this.messages = await res.json();
            }
        },

        async sendReply() {
            if (!this.replyText.trim() || this.sending || !this.selectedConvoId) return;
            this.sending = true;
            const text = this.replyText;
            this.replyText = '';

            try {
                const res = await fetch(`/admin/chat/${this.selectedConvoId}/reply`, {
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
            if (this.$refs.chatBottom) {
                this.$refs.chatBottom.scrollIntoView({ behavior: 'smooth' });
            }
        },

        formatTime(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },
    }));
</script>
@endsection
