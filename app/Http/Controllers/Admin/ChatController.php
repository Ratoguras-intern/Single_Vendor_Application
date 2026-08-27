<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $query = Conversation::with('user', 'latestMessage.sender');

        if ($request->filled('status') && in_array($request->status, ['open', 'closed'], true)) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'open');
        }

        if ($request->filled('unassigned')) {
            $query->whereNull('admin_id');
        }

        $conversations = $query->latest('last_message_at')->paginate(20)->withQueryString();
        $openCount = Conversation::where('status', 'open')->count();
        $unassignedCount = Conversation::where('status', 'open')->whereNull('admin_id')->count();

        $users = Conversation::query()
            ->with(['user' => fn ($q) => $q->select('id', 'name', 'email', 'avatar_path'), 'latestMessage.sender'])
            ->when($request->filled('status') && in_array($request->status, ['open', 'closed'], true), fn ($q) => $q->where('status', $request->status), fn ($q) => $q->where('status', 'open'))
            ->when($request->filled('unassigned'), fn ($q) => $q->whereNull('admin_id'))
            ->orderByDesc('last_message_at')
            ->get()
            ->unique('user_id')
            ->values();

        return view('admin.chat.index', compact('conversations', 'openCount', 'unassignedCount', 'users'));
    }

    public function show(Conversation $conversation): View
    {
        if (is_null($conversation->admin_id)) {
            $conversation->update(['admin_id' => auth()->id()]);
        }

        $conversation->load('user', 'messages.sender');

        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.chat.show', ['conversation' => $conversation]);
    }

    public function reply(Request $request, Conversation $conversation): JsonResponse
    {
        if ($conversation->status === 'closed') {
            return response()->json(['error' => 'This conversation is closed.'], 422);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        if (is_null($conversation->admin_id)) {
            $conversation->update(['admin_id' => auth()->id()]);
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        try { broadcast(new NewChatMessage($message)); } catch (\Throwable $e) {}

        return response()->json($message->load('sender'));
    }

    public function close(Conversation $conversation)
    {
        $conversation->update(['status' => 'closed']);

        return redirect()->route('admin.chat.show', $conversation)->with('success', 'Conversation closed.');
    }

    public function reopen(Conversation $conversation)
    {
        $conversation->update(['status' => 'open']);

        return redirect()->route('admin.chat.show', $conversation)->with('success', 'Conversation reopened.');
    }

    public function messages(Conversation $conversation): JsonResponse
    {
        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->get();

        return response()->json($messages);
    }
}
