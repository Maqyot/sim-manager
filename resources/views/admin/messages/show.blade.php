@extends('layouts.app')
@section('title', 'Conversation · '.$student->full_name)
@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <div class="small text-body-secondary mb-2">SIMS / Student messages</div>
        <h1 class="h2 fw-bold mb-1">{{ $student->full_name }}</h1>
        <p class="text-body-secondary mb-0">{{ $student->student_number }} · {{ $student->email }}</p>
    </div>
    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">All conversations</a>
</div>
<section class="card shadow-sm" style="border-radius: 1rem">
    <div class="card-body p-0">
        <div class="p-4 border-bottom">
            <h2 class="h5 fw-semibold mb-1">Private conversation</h2>
            <p class="small text-body-secondary mb-0">Only this student and admins can view these messages.</p>
        </div>
        <div class="p-4 d-flex flex-column gap-3">
            @forelse ($messages->reverse() as $message)
                <article class="align-self-{{ $message->sender_role === 'student' ? 'start' : 'end' }} rounded-3 p-3 {{ $message->sender_role === 'student' ? 'bg-body-tertiary' : 'bg-primary text-white' }}" style="max-width: min(85%, 42rem)">
                    <div class="small {{ $message->sender_role === 'student' ? 'text-body-secondary' : 'text-white-50' }} mb-1">
                        {{ $message->sender_role === 'student' ? $student->first_name : 'Admin' }} · {{ $message->created_at->format('M j, Y · g:i A') }}
                    </div>
                    <p class="mb-0" style="white-space: pre-wrap">{{ $message->body }}</p>
                </article>
            @empty
                <p class="text-center text-body-secondary py-4 mb-0">No messages in this conversation yet.</p>
            @endforelse
        </div>
        @if ($messages->hasPages())
            <div class="px-4 pb-3">@include('partials.pager', ['paginator' => $messages])</div>
        @endif
        <form method="POST" action="{{ route('admin.messages.store', $student) }}" class="p-4 border-top">
            @csrf
            <label for="body" class="form-label fw-semibold">Reply to {{ $student->first_name }}</label>
            <textarea id="body" name="body" rows="3" maxlength="2000" required class="form-control @error('body') is-invalid @enderror" placeholder="Write a reply...">{{ old('body') }}</textarea>
            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="text-end mt-3"><button class="btn btn-primary">Send reply</button></div>
        </form>
    </div>
</section>
@endsection
