<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{id}', function ($user, $id) {
    $conversation = \App\Models\Conversation::find($id);
    if (! $conversation) {
        return false;
    }

    if ($user->id === $conversation->user_id || $user->id === $conversation->admin_id) {
        return true;
    }

    if (in_array($user->role, ['admin', 'super_admin'])) {
        return true;
    }

    return false;
});

Broadcast::channel('chat.admin', function ($user) {
    if (in_array($user->role, ['admin', 'super_admin'])) {
        return true;
    }
    return false;
});
