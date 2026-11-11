@extends('layouts.app')
@section('title', 'Messages to Admin')
@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="mb-4">
            <div class="small text-body-secondary mb-2">Student portal / Communication</div>
            <h1 class="h2 fw-bold mb-2">Messages to Admin</h1>
            <p class="text-body-secondary mb-0">Your messages are private to you and the admin team.</p>
        </div>
        <section class="card shadow-sm" style="border-radius: 1rem">
            <div class="card-body p-0">
                <div class="p-4 border-bottom">
                    <h2 class="h5 fw-semibold mb-1">Your conversation</h2>
                    <p class="small text-body-secondary mb-0">Send a message or read the admin team's replies.</p>
                </div>
                <div class="p-4 d-flex flex-column gap-3">
                    @forelse ($messages->reverse() as $message)
                        <article class="align-self-{{ $message->sender_role === 'student' ? 'end' : 'start' }} rounded-3 p-3 {{ $message->sender_role === 'student' ? 'bg-primary text-white' : 'bg-body-tertiary' }}" style="max-width: min(85%, 42rem)">
                            <div class="small {{ $message->sender_role === 'student' ? 'text-white-50' : 'text-body-secondary' }} mb-1">
                                {{ $message->sender_role === 'student' ? 'You' : 'Admin' }} · {{ $message->created_at->format('M j, Y · g:i A') }}
                            </div>
                            <p class="mb-0" style="white-space: pre-wrap">{{ $message->body }}</p>
                        </article>
                    @empty
                        <p class="text-center text-body-secondary py-4 mb-0">No messages yet. Send a message to start a private conversation with the admin team.</p>
                    @endforelse
                </div>
                @if ($messages->hasPages())
                    <div class="px-4 pb-3">@include('partials.pager', ['paginator' => $messages])</div>
                @endif
                <form method="POST" action="{{ route('student.messages.store') }}" class="p-4 border-top">
                    @csrf
                    <label for="body" class="form-label fw-semibold">Write a message</label>
                    <textarea id="body" name="body" rows="3" maxlength="2000" required class="form-control @error('body') is-invalid @enderror" placeholder="Type your message to the admin team...">{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="d-flex justify-content-between align-items-center gap-2 mt-3">
                        <span class="small text-body-secondary">Maximum 2,000 characters</span>
                        <button class="btn btn-primary">Send message</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
