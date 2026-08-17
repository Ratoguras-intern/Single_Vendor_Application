@extends('layouts.frontend')

@section('title', 'Request Return - Order #' . $order->order_number)

@php
    use App\Support\ReturnStatuses;
@endphp

@section('content')
<section x-data="returnForm()" class="py-8 sm:py-12">
    <div class="section max-w-3xl mx-auto">
        <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 mb-6">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Back to Order
        </a>

        <div class="mb-6">
            <h1 class="page-heading">Request Return</h1>
            <p class="text-sm text-secondary-500 dark:text-secondary-400 mt-1">Order {{ $order->order_number }} &bull; {{ $order->items->count() }} item(s)</p>
        </div>

        <form method="POST" action="{{ route('customer.returns.store', $order) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            @foreach ($order->items as $item)
                @php
                    $alreadyReturned = $existingReturnQty->get($item->id, 0);
                    $maxAvailable = $item->quantity - $alreadyReturned;
                    $itemKey = $loop->index;
                @endphp

                @if ($maxAvailable > 0)
                    <div class="card" x-data="{ open: true }">
                        <div class="flex items-center gap-4">
                            @if ($item->product && $item->product->primaryImage())
                                <img src="{{ $item->product->primaryImage()->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-card object-cover shrink-0" />
                            @else
                                <div class="w-16 h-16 rounded-card bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center shrink-0">
                                    <svg class="h-6 w-6 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-secondary-900 dark:text-white">{{ $item->product->name ?? 'Product #' . $item->product_id }}</p>
                                <p class="text-sm text-secondary-500 dark:text-secondary-400">Purchased: {{ $item->quantity }} &bull; <span x-text="$store.currency.format({{ $item->price }})"></span> each</p>
                                @if ($alreadyReturned > 0)
                                    <p class="text-xs text-orange-600 dark:text-orange-400">{{ $alreadyReturned }} already returned</p>
                                @endif
                            </div>
                            <p class="font-bold text-secondary-900 dark:text-white"><span x-text="$store.currency.format({{ $item->price }})"></span></p>
                        </div>

                        <input type="hidden" name="items[{{ $itemKey }}][order_item_id]" value="{{ $item->id }}" />

                        <div class="mt-4 grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Qty to Return <span class="text-red-500">*</span></label>
                                <select name="items[{{ $itemKey }}][quantity]" required class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white">
                                    @for ($qty = 1; $qty <= $maxAvailable; $qty++)
                                        <option value="{{ $qty }}" {{ $qty === $maxAvailable && $maxAvailable === 1 ? 'selected' : '' }}>{{ $qty }}</option>
                                    @endfor
                                </select>
                                @error("items.{$itemKey}.quantity")
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Reason <span class="text-red-500">*</span></label>
                                <select name="items[{{ $itemKey }}][reason]" required class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white">
                                    <option value="">Select a reason</option>
                                    @foreach (ReturnStatuses::returnReasons() as $key => $label)
                                        <option value="{{ $key }}" {{ old("items.{$itemKey}.reason") === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error("items.{$itemKey}.reason")
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Note <span class="text-secondary-400">(optional)</span></label>
                            <textarea name="items[{{ $itemKey }}][customer_note]" rows="2" maxlength="500" placeholder="Describe the issue..." class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm placeholder:text-secondary-400 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white dark:placeholder:text-secondary-500">{{ old("items.{$itemKey}.customer_note") }}</textarea>
                            @error("items.{$itemKey}.customer_note")
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="mb-1.5 block text-sm font-medium text-secondary-700 dark:text-secondary-300">Evidence Images <span class="text-secondary-400">(optional, max 5)</span></label>
                            <input type="file" name="items[{{ $itemKey }}][evidence][]" multiple accept="image/jpg,image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-secondary-300 bg-white px-3 py-2.5 text-sm text-secondary-900 shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-primary-600 hover:file:bg-primary-100 dark:border-secondary-600 dark:bg-secondary-800 dark:text-white dark:file:bg-primary-950/30 dark:file:text-primary-400" />
                            @error("items.{$itemKey}.evidence")
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="flex items-center gap-3 pt-4">
                <a href="{{ route('customer.orders.show', $order) }}" class="flex-1 text-center rounded-lg border border-secondary-300 bg-white px-4 py-2.5 text-sm font-medium text-secondary-700 hover:bg-secondary-50 dark:border-secondary-600 dark:bg-secondary-800 dark:text-secondary-300 dark:hover:bg-secondary-700">Cancel</a>
                <button type="submit" @click.prevent="$store.confirmModal.open({ title: 'Submit Return Request', message: 'Submit return request?', form: $el.closest('form') })" class="flex-1 rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-secondary-900">Submit Return Request</button>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
function returnForm() {
    return {
        init() {}
    }
}
</script>
@endpush
@endsection
