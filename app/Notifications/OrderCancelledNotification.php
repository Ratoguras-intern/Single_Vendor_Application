<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
{
    public function __construct(
        public Order $order,
        public string $cancelledBy,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'cancelled_by' => $this->cancelledBy,
            'message' => "Order #{$this->order->order_number} was cancelled by {$this->cancelledBy}.",
        ];
    }
}
