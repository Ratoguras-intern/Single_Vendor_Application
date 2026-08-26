<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturn extends Model
{
    use SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'user_id',
        'status',
        'requested_at',
        'approved_at',
        'rejected_at',
        'received_at',
        'refunded_at',
        'admin_notes',
        'rejection_reason',
        'return_instructions',
        'tracking_number',
        'carrier',
        'refund_method',
        'refund_reference',
        'refund_amount',
        'restock',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'received_at' => 'datetime',
        'refunded_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'restock' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return \App\Support\ReturnStatuses::label($this->status);
    }

    public static function generateReturnNumber(): string
    {
        $last = static::orderByDesc('id')->value('return_number');

        if ($last && preg_match('/^RET-(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        } else {
            $next = 1;
        }

        return 'RET-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }

    public static function calculateRefundAmount(OrderReturn $return): float
    {
        return (float) $return->items->sum(function (ReturnItem $item) {
            return $item->unit_price * $item->quantity;
        });
    }
}
