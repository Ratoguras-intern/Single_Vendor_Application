<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class HomepageSectionOrderChangedNotification extends Notification
{
    public function __construct(
        public string $changedBy,
        public array $order,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->changedBy} reordered homepage sections.",
            'order' => $this->order,
        ];
    }
}
