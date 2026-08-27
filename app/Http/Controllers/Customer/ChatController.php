<?php

namespace App\Http\Controllers\Customer;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function conversations(Request $request): JsonResponse
    {
        $conversations = Conversation::forUser($request->user()->id)
            ->with('latestMessage')
            ->latest('last_message_at')
            ->get();

        return response()->json($conversations);
    }

    public function open(Request $request): JsonResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $conversation = Conversation::create([
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        try { broadcast(new NewChatMessage($message)); } catch (\Throwable $e) {}

        return response()->json([
            'conversation' => $conversation->load('latestMessage'),
            'message' => $message->load('sender'),
        ], 201);
    }

    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        if ($conversation->user_id !== $request->user()->id) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->get();

        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        if ($conversation->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($conversation->status === 'closed') {
            return response()->json(['error' => 'This conversation is closed.'], 422);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        try { broadcast(new NewChatMessage($message)); } catch (\Throwable $e) {}

        return response()->json($message->load('sender'));
    }
}
