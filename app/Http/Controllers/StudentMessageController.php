<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentMessageController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->student;
        $messages = $student->messages()->with('sender')->latest()->paginate(50);

        return view('student.messages', compact('messages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);
        $user = $request->user();

        ChatMessage::create([
            'student_id' => $user->student_id,
            'sender_user_id' => $user->id,
            'sender_role' => $user->role,
            'body' => $data['body'],
        ]);

        return redirect()->route('student.messages')->with('success', 'Message sent to the admin team.');
    }
}
