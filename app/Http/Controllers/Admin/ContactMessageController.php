<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

        if ($request->filled('status') && in_array($request->status, ['new', 'read', 'replied'], true)) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20)->withQueryString();
        $newCount = ContactMessage::where('status', 'new')->count();

        return view('admin.contact-messages.index', compact('messages', 'newCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('admin.contact-messages.show', ['message' => $contactMessage]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
