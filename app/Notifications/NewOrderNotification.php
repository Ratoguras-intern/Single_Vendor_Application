<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total' => $this->order->total_amount,
            'customer' => $this->order->user->name,
            'message' => "New order #{$this->order->order_number} placed by {$this->order->user->name} — $" . number_format($this->order->total_amount, 2),
        ];
    }
}
