@extends('layouts.app')
@section('title', 'My Enrollments')
@section('content')
<div class="student-welcome p-4 p-lg-5 mb-4" style="background: linear-gradient(120deg, #173b70, #2864a8); border-radius: 1rem; color: #fff">
    <div class="small text-white-50 mb-2">Student portal / My learning</div>
    <h1 class="h2 fw-bold mb-2">My enrollments</h1>
    <p class="mb-0 text-white-50">Your subjects, class schedules, and enrollment status.</p>
</div>
<section class="card shadow-sm" style="border-radius: 1rem">
    <div class="card-body p-0">
        <div class="p-4 border-bottom">
            <h2 class="h5 fw-semibold mb-1">Enrollment history</h2>
            <p class="small text-body-secondary mb-0">{{ number_format($enrollments->total()) }} {{ \Illuminate\Support\Str::plural('record', $enrollments->total()) }}</p>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Subject</th><th>Schedule</th><th>Term</th><th>Status</th><th class="pe-4">Grade</th></tr></thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td class="ps-4"><span class="fw-semibold">{{ $enrollment->subject->code }}</span><span class="d-block small text-body-secondary">{{ $enrollment->subject->name }} · {{ $enrollment->subject->units }} units</span></td>
                            <td>
                                @if ($enrollment->subject->meeting_days && $enrollment->subject->start_time && $enrollment->subject->end_time)
                                    <span class="d-block">{{ implode(', ', $enrollment->subject->meeting_days) }}</span>
                                    <span class="small text-body-secondary">{{ \Illuminate\Support\Carbon::parse($enrollment->subject->start_time)->format('g:i A') }}–{{ \Illuminate\Support\Carbon::parse($enrollment->subject->end_time)->format('g:i A') }}</span>
                                @else
                                    <span class="text-body-secondary">Not scheduled</span>
                                @endif
                            </td>
                            <td>{{ $enrollment->school_year }} · {{ $enrollment->semester }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ ['enrolled' => 'primary', 'completed' => 'success', 'dropped' => 'secondary'][$enrollment->status] ?? 'light' }}">{{ ucfirst($enrollment->status) }}</span></td>
                            <td class="pe-4">{{ $enrollment->grade ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-body-secondary py-5">No enrollment records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">@include('partials.pager', ['paginator' => $enrollments])</div>
    </div>
</section>
@endsection
