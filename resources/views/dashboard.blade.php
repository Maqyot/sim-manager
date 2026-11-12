@extends('layouts.app')
@section('title', 'Dashboard')
@push('styles')
<style>
    .dashboard-heading {
        background: linear-gradient(120deg, #173b70, #2864a8);
        border-radius: 1rem;
        color: #fff;
    }

    .dashboard-stat,
    .dashboard-panel {
        border: 1px solid var(--bs-border-color);
        border-radius: 1rem;
    }

    .dashboard-stat-mark {
        align-items: center;
        border-radius: .85rem;
        display: inline-flex;
        font-size: .75rem;
        font-weight: 700;
        height: 2.75rem;
        justify-content: center;
        letter-spacing: .04em;
        width: 2.75rem;
    }

    .dashboard-stat-value {
        font-size: clamp(1.7rem, 3vw, 2.25rem);
        font-weight: 700;
        letter-spacing: -.04em;
    }

    .dashboard-progress {
        background: var(--bs-tertiary-bg);
        border-radius: 999px;
        height: .55rem;
        overflow: hidden;
    }

    .dashboard-progress-bar {
        background: #3478c8;
        border-radius: inherit;
        height: 100%;
    }

    .dashboard-table th {
        color: var(--bs-secondary-color);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
</style>
@endpush
@section('content')
@php
    $maxCourseCount = max(1, (int) $courseCounts->max());
    $activeEnrollmentCount = (int) $statusCounts->get('enrolled', 0);
@endphp
<div class="dashboard-heading p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="small text-white-50 mb-2">SIMS / Workspace</div>
            <h1 class="h2 fw-bold mb-2">Good day, {{ auth()->user()->name }}</h1>
            <p class="mb-0 text-white-50">Here is the latest overview of your academic records.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('students.create') }}" class="btn btn-light fw-semibold">Add student</a>
            <a href="{{ route('enrollments.create') }}" class="btn btn-outline-light">New enrollment</a>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['Students', $studentCount, 'ST', 'text-primary bg-primary-subtle', 'students.index', 'Student records'],
        ['Subjects', $subjectCount, 'SU', 'text-success bg-success-subtle', 'subjects.index', 'Available subjects'],
        ['Enrollments', $enrollmentCount, 'EN', 'text-warning-emphasis bg-warning-subtle', 'enrollments.index', 'All recorded enrollments'],
        ['Currently enrolled', $activeEnrollmentCount, 'AC', 'text-info-emphasis bg-info-subtle', 'enrollments.index', 'Active enrollment records'],
    ] as [$label, $count, $mark, $markClass, $route, $caption])
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route($route) }}" class="card dashboard-stat shadow-sm h-100 text-decoration-none text-body">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="small text-body-secondary">{{ $label }}</span>
                        <span class="dashboard-stat-mark {{ $markClass }}" aria-hidden="true">{{ $mark }}</span>
                    </div>
                    <div class="dashboard-stat-value">{{ number_format($count) }}</div>
                    <div class="small text-body-secondary mt-1">{{ $caption }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>

@if (auth()->user()->isAdmin())
    <section class="card dashboard-panel shadow-sm mb-4" aria-labelledby="student-registration-queue-title">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center gap-2 p-4 border-bottom">
                <div>
                    <h2 class="h5 fw-semibold mb-1" id="student-registration-queue-title">Student registration approvals</h2>
                    <p class="small text-body-secondary mb-0">Review new student accounts before they can sign in.</p>
                </div>
                <span class="badge rounded-pill text-bg-warning">{{ $pendingStudentRegistrations->count() }} pending</span>
            </div>
            <div class="list-group list-group-flush">
                @forelse ($pendingStudentRegistrations as $registration)
                    <div class="list-group-item p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <div class="fw-semibold">{{ $registration->student->full_name }}</div>
                            <div class="small text-body-secondary">{{ $registration->student->student_number }} · {{ $registration->student->email }}</div>
                            <div class="small text-body-secondary">{{ $registration->student->course }} · Year {{ $registration->student->year_level }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.student-registrations.approve', $registration) }}">
                            @csrf
                            <button class="btn btn-success">Approve student</button>
                        </form>
                    </div>
                @empty
                    <div class="p-4 text-body-secondary">No student registrations are waiting for approval.</div>
                @endforelse
            </div>
        </div>
    </section>
@endif

<div class="row g-3 mb-4">
    <div class="col-xl-7">
        <section class="card dashboard-panel shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="h5 fw-semibold mb-1">Students by course</h2>
                        <p class="small text-body-secondary mb-0">Distribution across registered courses</p>
                    </div>
                    <a href="{{ route('students.index') }}" class="small text-decoration-none">View students</a>
                </div>
                @forelse ($courseCounts as $course => $total)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">{{ $course }}</span>
                            <span class="text-body-secondary">{{ number_format($total) }} {{ \Illuminate\Support\Str::plural('student', $total) }}</span>
                        </div>
                        <div class="dashboard-progress" role="progressbar" aria-label="{{ $course }} students" aria-valuenow="{{ $total }}" aria-valuemin="0" aria-valuemax="{{ $maxCourseCount }}">
                            <div class="dashboard-progress-bar" style="width: {{ round(($total / $maxCourseCount) * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-body-secondary py-5">Course distribution will appear when students are added.</div>
                @endforelse
            </div>
        </section>
    </div>
    <div class="col-xl-5">
        <section class="card dashboard-panel shadow-sm h-100">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h2 class="h5 fw-semibold mb-1">Enrollment status</h2>
                    <p class="small text-body-secondary mb-0">Current records by status</p>
                </div>
                @forelse ($statusCounts as $status => $total)
                    @php
                        $statusClass = match ($status) {
                            'enrolled' => 'text-bg-success',
                            'completed' => 'text-bg-primary',
                            'dropped' => 'text-bg-secondary',
                            default => 'text-bg-light',
                        };
                    @endphp
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill {{ $statusClass }}" aria-hidden="true">&nbsp;</span>
                            <span class="fw-medium">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="fw-semibold">{{ number_format($total) }}</span>
                    </div>
                @empty
                    <div class="text-center text-body-secondary py-5">Enrollment status will appear when records are added.</div>
                @endforelse
                <a href="{{ route('enrollments.index') }}" class="btn btn-outline-primary btn-sm mt-4">Manage enrollments</a>
            </div>
        </section>
    </div>
</div>

<section class="card dashboard-panel shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 p-4 border-bottom">
            <div>
                <h2 class="h5 fw-semibold mb-1">Recent enrollments</h2>
                <p class="small text-body-secondary mb-0">The latest enrollment activity</p>
            </div>
            <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table dashboard-table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Student</th><th>Subject</th><th>Term</th><th class="pe-4">Status</th></tr></thead>
                <tbody>
                    @forelse ($recentEnrollments as $e)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $e->student->first_name }} {{ $e->student->last_name }}</td>
                            <td>{{ $e->subject->code }}</td>
                            <td>{{ $e->school_year }} · {{ $e->semester }}</td>
                            <td class="pe-4"><span class="badge rounded-pill text-bg-light border">{{ ucfirst($e->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-body-secondary py-5">No enrollment activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="card dashboard-panel shadow-sm mt-4">
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
