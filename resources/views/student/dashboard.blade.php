@extends('layouts.app')
@section('title', 'Student Dashboard')
@push('styles')
<style>
    .student-welcome,
    .student-stat,
    .student-panel {
        border-radius: 1rem;
    }

    .student-welcome {
        background: linear-gradient(120deg, #173b70, #2864a8);
        color: #fff;
    }

    .student-stat,
    .student-panel {
        border: 1px solid var(--bs-border-color);
    }

    .student-stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: -.04em;
    }

    .student-table th {
        color: var(--bs-secondary-color);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
</style>
@endpush
@section('content')
<div class="student-welcome p-4 p-lg-5 mb-4">
    <div class="small text-white-50 mb-2">Student portal / Dashboard</div>
    <h1 class="h2 fw-bold mb-2">Welcome, {{ $student->first_name }}</h1>
    <p class="mb-0 text-white-50">{{ $student->course }} · Year {{ $student->year_level }} · {{ $student->student_number }}</p>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['My enrollments', $enrollmentCount, 'All your recorded subjects'],
        ['Currently enrolled', $activeEnrollments->count(), 'Active class registrations'],
        ['Completed', $completedCount, 'Completed enrollments'],
    ] as [$label, $count, $caption])
        <div class="col-sm-6 col-xl-4">
            <div class="card student-stat shadow-sm h-100"><div class="card-body p-4">
                <div class="small text-body-secondary mb-2">{{ $label }}</div>
                <div class="student-stat-value">{{ number_format($count) }}</div>
                <div class="small text-body-secondary mt-1">{{ $caption }}</div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-5">
        <section class="card student-panel shadow-sm h-100" id="todays-schedule">
            <div class="card-body p-4">
                <div class="mb-3">
                    <h2 class="h5 fw-semibold mb-1">Today's schedule</h2>
                    <p class="small text-body-secondary mb-0">{{ $today }}, {{ now()->format('F j, Y') }}</p>
                </div>
                @forelse ($todaySchedule as $enrollment)
                    <div class="border-top py-3">
                        <div class="fw-semibold">{{ $enrollment->subject->code }} · {{ $enrollment->subject->name }}</div>
                        <div class="small text-body-secondary">
                            {{ \Illuminate\Support\Carbon::parse($enrollment->subject->start_time)->format('g:i A') }}–{{ \Illuminate\Support\Carbon::parse($enrollment->subject->end_time)->format('g:i A') }}
                            · {{ $enrollment->school_year }} / {{ $enrollment->semester }}
                        </div>
                    </div>
                @empty
                    <div class="border-top py-4 text-body-secondary">No enrolled classes are scheduled for today.</div>
                @endforelse
            </div>
        </section>
    </div>
    <div class="col-xl-7">
        <section class="card student-panel shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="h5 fw-semibold mb-1">My current subjects</h2>
                        <p class="small text-body-secondary mb-0">Your active enrollment schedule</p>
                    </div>
                    <a href="{{ route('student.enrollments') }}" class="small text-decoration-none">All enrollments</a>
                </div>
                @forelse ($activeEnrollments as $enrollment)
                    <div class="border-top py-3 d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">{{ $enrollment->subject->code }} · {{ $enrollment->subject->name }}</div>
                            <div class="small text-body-secondary">
                                @if ($enrollment->subject->meeting_days && $enrollment->subject->start_time && $enrollment->subject->end_time)
                                    {{ implode(', ', $enrollment->subject->meeting_days) }} · {{ \Illuminate\Support\Carbon::parse($enrollment->subject->start_time)->format('g:i A') }}–{{ \Illuminate\Support\Carbon::parse($enrollment->subject->end_time)->format('g:i A') }}
                                @else
                                    Schedule not posted
                                @endif
                            </div>
                        </div>
                        <span class="badge rounded-pill text-bg-primary align-self-start">{{ $enrollment->subject->units }} units</span>
                    </div>
                @empty
                    <div class="border-top py-4 text-body-secondary">You have no current enrollments.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<section class="card student-panel shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center gap-2 p-4 border-bottom">
            <div>
                <h2 class="h5 fw-semibold mb-1">Recent enrollment activity</h2>
                <p class="small text-body-secondary mb-0">Your latest enrollment records</p>
            </div>
            <a href="{{ route('student.enrollments') }}" class="btn btn-outline-secondary btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table student-table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Subject</th><th>Term</th><th>Status</th><th class="pe-4">Grade</th></tr></thead>
                <tbody>
                    @forelse ($recentEnrollments as $enrollment)
                        <tr>
                            <td class="ps-4"><span class="fw-semibold">{{ $enrollment->subject->code }}</span><span class="d-block small text-body-secondary">{{ $enrollment->subject->name }}</span></td>
                            <td>{{ $enrollment->school_year }} · {{ $enrollment->semester }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ ['enrolled' => 'primary', 'completed' => 'success', 'dropped' => 'secondary'][$enrollment->status] ?? 'light' }}">{{ ucfirst($enrollment->status) }}</span></td>
                            <td class="pe-4">{{ $enrollment->grade ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-5">Your enrollment records will appear here.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="card student-panel shadow-sm mt-4">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center gap-2 p-4 border-bottom"><div><h2 class="h5 fw-semibold mb-1">Upcoming school events</h2><p class="small text-body-secondary mb-0">Campus activities and important dates</p></div><a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-sm">View calendar</a></div>
        <div class="list-group list-group-flush">
            @forelse($upcomingEvents as $event)
                <div class="list-group-item px-4 py-3 d-flex justify-content-between gap-2"><div><div class="fw-semibold">{{ $event->title }}</div><div class="small text-body-secondary">{{ $event->location ?: 'Location to be announced' }}</div></div><div class="text-end"><div>{{ $event->starts_at->format('M j, Y') }}</div><div class="small text-body-secondary">{{ $event->starts_at->format('g:i A') }}</div></div></div>
            @empty<div class="p-4 text-body-secondary">No upcoming school events have been posted.</div>@endforelse
        </div>
    </div>
</section>
@endsection
