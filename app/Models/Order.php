<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'total_amount',
        'status',
        'shipping_address',
        'billing_address',
        'tracking_number',
        'tracking_carrier',
        'phone',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return \App\Support\OrderStatuses::label($this->status);
    }

    public function getStatusStepAttribute(): ?int
    {
        return \App\Support\OrderStatuses::step($this->status);
    }

    public function getNextStatusAttribute(): ?string
    {
        return \App\Support\OrderStatuses::next($this->status);
    }
}
