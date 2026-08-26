@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Review Details</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review by {{ $review->user->name ?? 'Deleted User' }}</p>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Back to Reviews
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Review Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Review</h3>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $review->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                           ($review->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                           'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>

                <div class="flex items-center gap-0.5 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 dark:text-gray-600 fill-gray-300 dark:fill-gray-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endfor
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">{{ $review->rating }}/5</span>
                </div>

                @if($review->title)
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $review->title }}</h4>
                @endif

                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $review->comment }}</p>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>{{ $review->created_at->format('F j, Y \a\t g:i A') }}</span>
                    @if($review->is_verified_purchase)
                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Verified Purchase
                        </span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Actions</h3>
                <div class="flex flex-wrap items-center gap-3">
                    @if($review->status !== 'approved')
                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                Approve
                            </button>
                        </form>
                    @endif
                    @if($review->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                Reject
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Review', message: 'Are you sure you want to permanently delete this review?', form: $el })">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Customer --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Customer</h3>
                <p class="font-medium text-gray-800 dark:text-white">{{ $review->user->name ?? 'Deleted' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review->user->email ?? '' }}</p>
            </div>

            {{-- Product --}}
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Product</h3>
                <p class="font-medium text-gray-800 dark:text-white">{{ $review->product->name ?? 'Deleted' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $review->product_id }}</p>
            </div>

            {{-- Order --}}
            @if($review->order)
                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Order</h3>
                    <p class="font-medium text-gray-800 dark:text-white">#{{ $review->order->order_number }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status: {{ $review->order->status }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
