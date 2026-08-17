<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderReturn;
use App\Models\User;
use App\Notifications\ReturnStatusUpdatedNotification;
use App\Support\ReturnStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReturn::with(['order', 'user', 'items.product']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($oq) => $oq->where('order_number', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $returns = $query->latest()->paginate(15);
        $statusCounts = OrderReturn::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');

        return view('admin.returns.index', compact('returns', 'statusCounts'));
    }

    public function show(OrderReturn $return)
    {
        $return->load(['order', 'user', 'items.product', 'items.evidence', 'items.orderItem']);

        return view('admin.returns.show', compact('return'));
    }

    public function approve(Request $request, OrderReturn $return)
    {
        if (! ReturnStatuses::canApprove($return->status)) {
            return back()->with('error', 'This return cannot be approved.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'return_instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $return->status;
        $return->update([
            'status' => ReturnStatuses::APPROVED,
            'approved_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? $return->admin_notes,
            'return_instructions' => $validated['return_instructions'] ?? $return->return_instructions,
        ]);

        $return->user->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::APPROVED));

        return back()->with('success', 'Return approved.');
    }

    public function reject(Request $request, OrderReturn $return)
    {
        if (! ReturnStatuses::canReject($return->status)) {
            return back()->with('error', 'This return cannot be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $oldStatus = $return->status;
        $return->update([
            'status' => ReturnStatuses::REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $return->user->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::REJECTED));

        return back()->with('success', 'Return rejected.');
    }

    public function requestMoreInfo(Request $request, OrderReturn $return)
    {
        if (! ReturnStatuses::canRequestMoreInfo($return->status)) {
            return back()->with('error', 'Cannot request more information for this return.');
        }

        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $oldStatus = $return->status;
        $return->update([
            'status' => ReturnStatuses::MORE_INFO_REQUIRED,
            'admin_notes' => $validated['admin_notes'],
        ]);

        $return->user->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::MORE_INFO_REQUIRED));

        return back()->with('success', 'Customer has been notified to provide more information.');
    }

    public function markReceived(Request $request, OrderReturn $return)
    {
        if (! ReturnStatuses::canMarkReceived($return->status)) {
            return back()->with('error', 'This return cannot be marked as received.');
        }

        $validated = $request->validate([
            'restock' => ['required', 'boolean'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $return->status;
            $return->update([
                'status' => ReturnStatuses::RECEIVED,
                'received_at' => now(),
                'restock' => $validated['restock'],
                'admin_notes' => $validated['admin_notes'] ?? $return->admin_notes,
            ]);

            if ($validated['restock']) {
                foreach ($return->items as $returnItem) {
                    Product::where('id', $returnItem->product_id)
                        ->increment('stock', $returnItem->quantity);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark as received.');
        }

        $return->user->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::RECEIVED));

        return back()->with('success', 'Return items received' . ($validated['restock'] ? ' and restocked.' : '.'));
    }

    public function processRefund(Request $request, OrderReturn $return)
    {
        if (! ReturnStatuses::canRefund($return->status)) {
            return back()->with('error', 'This return cannot be refunded yet.');
        }

        $validated = $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:0.01', 'lte:' . $return->refund_amount],
            'refund_method' => ['required', 'string', 'in:original,store_credit,manual'],
            'refund_reference' => ['nullable', 'string', 'max:100'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $return->status;
        $return->update([
            'status' => ReturnStatuses::REFUNDED,
            'refunded_at' => now(),
            'refund_amount' => $validated['refund_amount'],
            'refund_method' => $validated['refund_method'],
            'refund_reference' => $validated['refund_reference'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? $return->admin_notes,
        ]);

        $return->user->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::REFUNDED));

        return back()->with('success', 'Refund recorded successfully.');
    }

    public function complete(OrderReturn $return)
    {
        if ($return->status !== ReturnStatuses::REFUNDED) {
            return back()->with('error', 'Only refunded returns can be completed.');
        }

        $oldStatus = $return->status;
        $return->update(['status' => ReturnStatuses::COMPLETED]);

        $return->user->notify(new ReturnStatusUpdatedNotification($return, $oldStatus, ReturnStatuses::COMPLETED));

        return back()->with('success', 'Return marked as completed.');
    }
}
