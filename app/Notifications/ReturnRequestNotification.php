<?php

namespace App\Notifications;

use App\Models\OrderReturn;
use Illuminate\Notifications\Notification;

class ReturnRequestNotification extends Notification
{
    public function __construct(
        public OrderReturn $return,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'return_request',
            'return_id' => $this->return->id,
            'return_number' => $this->return->return_number,
            'order_id' => $this->return->order_id,
            'order_number' => $this->return->order->order_number,
            'customer_name' => $this->return->user->name,
            'items_count' => $this->return->items->count(),
            'message' => "New return request {$this->return->return_number} for order #{$this->return->order->order_number}.",
        ];
    }
}
