<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMessageController extends Controller
{
    public function index(): View
    {
        $students = Student::query()
            ->whereHas('messages')
            ->with(['latestMessage.sender'])
            ->withCount('messages')
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->paginate(15);

        return view('admin.messages.index', compact('students'));
    }

    public function show(Student $student): View
    {
        $messages = $student->messages()->with('sender')->latest()->paginate(50);

        return view('admin.messages.show', compact('student', 'messages'));
    }

    public function store(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);
        $user = $request->user();

        ChatMessage::create([
            'student_id' => $student->id,
            'sender_user_id' => $user->id,
            'sender_role' => $user->role,
            'body' => $data['body'],
        ]);

        return redirect()->route('admin.messages.show', $student)->with('success', 'Reply sent.');
    }
}
