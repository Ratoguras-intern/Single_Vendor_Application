<?php

namespace App\Notifications;

use App\Models\OrderReturn;
use Illuminate\Notifications\Notification;

class ReturnStatusUpdatedNotification extends Notification
{
    public function __construct(
        public OrderReturn $return,
        public string $oldStatus,
        public string $newStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'return_status_updated',
            'return_id' => $this->return->id,
            'return_number' => $this->return->return_number,
            'order_id' => $this->return->order_id,
            'order_number' => $this->return->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Your return request {$this->return->return_number} has been updated to " . \App\Support\ReturnStatuses::label($this->newStatus) . ".",
        ];
    }
}
