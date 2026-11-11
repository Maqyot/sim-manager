@extends('layouts.app')
@section('title', 'Student Messages')
@section('content')
<div class="mb-4">
    <div class="small text-body-secondary mb-2">SIMS / Communication</div>
    <h1 class="h2 fw-bold mb-2">Student messages</h1>
    <p class="text-body-secondary mb-0">Private conversations between students and the admin team.</p>
</div>
<section class="card shadow-sm" style="border-radius: 1rem">
    <div class="card-body p-0">
        <div class="p-4 border-bottom">
            <h2 class="h5 fw-semibold mb-1">Conversations</h2>
            <p class="small text-body-secondary mb-0">{{ number_format($students->total()) }} {{ \Illuminate\Support\Str::plural('student', $students->total()) }} with messages</p>
        </div>
        <div class="list-group list-group-flush">
            @forelse ($students as $student)
                <a href="{{ route('admin.messages.show', $student) }}" class="list-group-item list-group-item-action p-4">
                    <div class="d-flex justify-content-between gap-3">
                        <div class="min-w-0">
                            <div class="fw-semibold">{{ $student->full_name }} <span class="text-body-secondary fw-normal">· {{ $student->student_number }}</span></div>
                            <div class="small text-body-secondary mt-1">{{ $student->latestMessage?->sender_role === 'student' ? 'Student' : 'Admin' }} · {{ $student->latestMessage?->body }}</div>
                        </div>
                        <div class="text-end text-nowrap">
                            <div class="small text-body-secondary">{{ $student->latestMessage?->created_at->format('M j, g:i A') }}</div>
                            <span class="badge rounded-pill text-bg-light border mt-2">{{ number_format($student->messages_count) }} {{ \Illuminate\Support\Str::plural('message', $student->messages_count) }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center text-body-secondary py-5">No student messages yet.</div>
            @endforelse
        </div>
        <div class="p-3">@include('partials.pager', ['paginator' => $students])</div>
    </div>
</section>
@endsection
