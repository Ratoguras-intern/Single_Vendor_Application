<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\ReturnItem;
use App\Models\User;
use App\Notifications\ReturnRequestNotification;
use App\Notifications\ReturnStatusUpdatedNotification;
use App\Support\OrderStatuses;
use App\Support\ReturnStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = OrderReturn::where('user_id', Auth::id())
            ->with(['order', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.returns.index', compact('returns'));
    }

    public function show(OrderReturn $return)
    {
        if ($return->user_id !== Auth::id()) {
            abort(403);
        }

        $return->load(['order', 'items.product', 'items.evidence']);

        return view('customer.returns.show', compact('return'));
    }

    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== OrderStatuses::DELIVERED) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Only delivered orders can be returned.');
        }

        $order->load('items.product');

        $returnWindowDays = config('returns.window_days', 14);
        $eligibleUntil = $order->created_at->addDays($returnWindowDays);
        $isWithinWindow = now()->lte($eligibleUntil);

        if (! $isWithinWindow) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', "The return window ({$returnWindowDays} days) for this order has expired.");
        }

        $existingReturnQty = OrderReturn::where('order_id', $order->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', [ReturnStatuses::REQUESTED, ReturnStatuses::PENDING_REVIEW, ReturnStatuses::MORE_INFO_REQUIRED, ReturnStatuses::APPROVED, ReturnStatuses::RETURN_SHIPPED])
            ->with('items')
            ->get()
            ->pluck('items')
            ->flatten()
            ->groupBy('order_item_id')
            ->map(fn ($items) => $items->sum('quantity'));

        return view('customer.returns.create', compact('order', 'existingReturnQty'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== OrderStatuses::DELIVERED) {
            return back()->with('error', 'Only delivered orders can be returned.');
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.reason' => ['required', 'string', 'in:' . implode(',', array_keys(ReturnStatuses::returnReasons()))],
            'items.*.customer_note' => ['nullable', 'string', 'max:500'],
            'items.*.evidence' => ['nullable', 'array', 'max:5'],
            'items.*.evidence.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $returnWindowDays = config('returns.window_days', 14);
        if (now()->gt($order->created_at->addDays($returnWindowDays))) {
            return back()->with('error', 'The return window for this order has expired.');
        }

        foreach ($validated['items'] as $item) {
            $orderItem = $order->items()->where('id', $item['order_item_id'])->first();
            if (! $orderItem) {
                return back()->with('error', 'Invalid order item.');
            }

            $alreadyReturned = OrderReturn::where('order_id', $order->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', [ReturnStatuses::REQUESTED, ReturnStatuses::PENDING_REVIEW, ReturnStatuses::MORE_INFO_REQUIRED, ReturnStatuses::APPROVED, ReturnStatuses::RETURN_SHIPPED])
                ->with('items')
                ->get()
                ->pluck('items')
                ->flatten()
                ->where('order_item_id', $orderItem->id)
                ->sum('quantity');

            if ($item['quantity'] > ($orderItem->quantity - $alreadyReturned)) {
                return back()->with('error', "Return quantity exceeds available quantity for item: {$orderItem->product->name}.");
            }
        }

        DB::beginTransaction();

        try {
            $return = OrderReturn::create([
                'return_number' => OrderReturn::generateReturnNumber(),
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'status' => ReturnStatuses::PENDING_REVIEW,
                'requested_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {
                $orderItem = $order->items()->with('product')->find($item['order_item_id']);

                $returnItem = ReturnItem::create([
                    'order_return_id' => $return->id,
                    'order_item_id' => $orderItem->id,
                    'product_id' => $orderItem->product_id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $orderItem->price,
                    'reason' => $item['reason'],
                    'customer_note' => $item['customer_note'] ?? null,
                ]);

                if (! empty($item['evidence'])) {
                    foreach ($item['evidence'] as $file) {
                        $path = $file->store('returns/evidence', 'public');
                        $returnItem->evidence()->create(['image_path' => $path]);
                    }
                }
            }

            $return->update(['refund_amount' => OrderReturn::calculateRefundAmount($return)]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create return request. Please try again.');
        }

        $admins = User::where('role', 'admin')->get();
        $superAdmins = User::where('role', 'super_admin')->get();
        $staff = $admins->merge($superAdmins);
        foreach ($staff as $admin) {
            $admin->notify(new ReturnRequestNotification($return));
        }

        return redirect()->route('customer.returns.show', $return)
            ->with('success', 'Return request submitted successfully.');
    }

    public function addInfo(Request $request, OrderReturn $return)
    {
        if ($return->user_id !== Auth::id()) {
            abort(403);
        }

        if ($return->status !== ReturnStatuses::MORE_INFO_REQUIRED) {
            return back()->with('error', 'This return is not awaiting additional information.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'evidence' => ['nullable', 'array', 'max:5'],
            'evidence.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $return->update(['status' => ReturnStatuses::PENDING_REVIEW]);

        if (! empty($validated['evidence'])) {
            $firstItem = $return->items->first();
            if ($firstItem) {
                foreach ($validated['evidence'] as $file) {
                    $path = $file->store('returns/evidence', 'public');
                    $firstItem->evidence()->create(['image_path' => $path]);
                }
            }
        }

        $admins = User::where('role', 'admin')->get();
        $superAdmins = User::where('role', 'super_admin')->get();
        $staff = $admins->merge($superAdmins);
        foreach ($staff as $admin) {
            $admin->notify(new ReturnStatusUpdatedNotification($return, ReturnStatuses::MORE_INFO_REQUIRED, ReturnStatuses::PENDING_REVIEW));
        }

        return back()->with('success', 'Information submitted. Your return is now under review again.');
    }

    public function shipReturn(Request $request, OrderReturn $return)
    {
        if ($return->user_id !== Auth::id()) {
            abort(403);
        }

        if ($return->status !== ReturnStatuses::APPROVED) {
            return back()->with('error', 'This return has not been approved yet.');
        }

        $validated = $request->validate([
            'carrier' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:100'],
        ]);

        $return->update([
            'status' => ReturnStatuses::RETURN_SHIPPED,
            'carrier' => $validated['carrier'],
            'tracking_number' => $validated['tracking_number'],
        ]);

        $admins = User::where('role', 'admin')->get();
        $superAdmins = User::where('role', 'super_admin')->get();
        $staff = $admins->merge($superAdmins);
        foreach ($staff as $admin) {
            $admin->notify(new ReturnStatusUpdatedNotification($return, ReturnStatuses::APPROVED, ReturnStatuses::RETURN_SHIPPED));
        }

        return back()->with('success', 'Return shipment details submitted.');
    }

    public function cancel(OrderReturn $return)
    {
        if ($return->user_id !== Auth::id()) {
            abort(403);
        }

        if (! ReturnStatuses::canCancel($return->status)) {
            return back()->with('error', 'This return cannot be cancelled.');
        }

        $oldStatus = $return->status;
        $return->update(['status' => ReturnStatuses::CANCELLED]);

        $admins = User::where('role', 'admin')->get();
        $superAdmins = User::where('role', 'super_admin')->get();
        $staff = $admins->merge($superAdmins);
        foreach ($staff as $admin) {
            $admin->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::CANCELLED));
        }

        return back()->with('success', 'Return request cancelled.');
    }
}
