@extends('layouts.app')
@section('title', $student->full_name)
@push('styles')
<style>
    .student-profile-hero,
    .student-profile-stat,
    .student-profile-panel {
        border-radius: 1rem;
    }

    .student-profile-hero {
        background: linear-gradient(120deg, #173b70, #2864a8);
        color: #fff;
    }

    .student-profile-stat,
    .student-profile-panel {
        border: 1px solid var(--bs-border-color);
    }

    .student-profile-avatar {
        align-items: center;
        background: rgba(255, 255, 255, .16);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 1rem;
        display: inline-flex;
        flex: 0 0 4.5rem;
        font-size: 1.4rem;
        font-weight: 700;
        height: 4.5rem;
        justify-content: center;
    }

    .student-profile-stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: -.04em;
    }

    .student-profile-label {
        color: var(--bs-secondary-color);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .student-profile-table th {
        color: var(--bs-secondary-color);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .student-profile-timeline {
        border-left: 2px solid var(--bs-border-color);
        margin-left: .5rem;
        padding-left: 1.25rem;
    }

    .student-profile-timeline-item {
        position: relative;
    }

    .student-profile-timeline-item::before {
        background: var(--bs-primary);
        border: 3px solid var(--bs-body-bg);
        border-radius: 50%;
        content: "";
        height: .85rem;
        left: -1.72rem;
        position: absolute;
        top: .25rem;
        width: .85rem;
    }

    .student-profile-timeline-item + .student-profile-timeline-item {
        margin-top: 1.5rem;
    }
}
</style>
@endpush
@section('content')
<div class="student-profile-hero p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div class="d-flex align-items-center gap-3">
            <div class="student-profile-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($student->first_name, 0, 1).\Illuminate\Support\Str::substr($student->last_name, 0, 1)) }}</div>
            <div>
                <div class="small text-white-50 mb-2">SIMS / Students / Student profile</div>
                <h1 class="h2 fw-bold mb-2">{{ $student->full_name }}</h1>
                <div class="text-white-50">{{ $student->course }} · Year {{ $student->year_level }}</div>
                <div class="small text-white-50 mt-1">Student no. {{ $student->student_number }}</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if ($student->user)
                <span class="badge rounded-pill text-bg-success">Student portal active</span>
            @else
                <span class="badge rounded-pill text-bg-light text-primary">No portal account</span>
            @endif
            <a href="{{ route('students.edit', $student) }}" class="btn btn-light fw-semibold">Edit student</a>
        </div>
    </div>
</div>

<nav class="nav nav-pills gap-2 mb-4" aria-label="Student profile sections">
    <a class="nav-link active" href="#student-overview">Overview</a>
    <a class="nav-link text-body" href="#personal-information">Personal information</a>
    <a class="nav-link text-body" href="#student-timeline">Timeline</a>
    <a class="nav-link text-body" href="#all-enrollments">Enrollments</a>
</nav>

<div class="row g-3 mb-4" id="student-overview">
    @foreach ([
        ['Current subjects', $enrolledCount, 'Active course registrations', 'text-primary'],
        ['Enrolled units', $enrolledUnits, 'Units across active subjects', 'text-success'],
        ['Completed subjects', $completedCount, 'Completed enrollments', 'text-info-emphasis'],
        ['All enrollments', $enrollments->count(), 'Current and past records', 'text-warning-emphasis'],
    ] as [$label, $count, $caption, $color])
        <div class="col-sm-6 col-xl-3">
            <div class="card student-profile-stat shadow-sm h-100">
                <div class="card-body p-3 p-lg-4">
                    <div class="small text-body-secondary mb-2">{{ $label }}</div>
                    <div class="student-profile-stat-value {{ $color }}">{{ number_format($count) }}</div>
                    <div class="small text-body-secondary mt-1">{{ $caption }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-7">
        <section class="card student-profile-panel shadow-sm h-100" id="personal-information">
            <div class="card-body p-4">
                <div class="small text-uppercase text-body-secondary fw-semibold mb-2">Student record</div>
                <h2 class="h5 fw-semibold mb-4">Personal information</h2>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="student-profile-label mb-1">Full name</div>
                        <div class="fw-medium">{{ $student->full_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="student-profile-label mb-1">Student number</div>
                        <div class="fw-medium">{{ $student->student_number }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="student-profile-label mb-1">Email address</div>
                        <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                    </div>
                    <div class="col-sm-6">
                        <div class="student-profile-label mb-1">Course</div>
                        <div class="fw-medium">{{ $student->course }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="student-profile-label mb-1">Year level</div>
                        <div class="fw-medium">Year {{ $student->year_level }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="student-profile-label mb-1">Student portal</div>
                        <div class="fw-medium">{{ $student->user ? 'Account active' : 'Not activated' }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-xl-5">
        <section class="card student-profile-panel shadow-sm h-100">
            <div class="card-body p-4">
                <div class="small text-uppercase text-body-secondary fw-semibold mb-2">What's coming up</div>
                @if ($nextClass)
                    @php($nextEnrollment = $nextClass['enrollment'])
                    <h2 class="h5 fw-semibold mb-1">Next subject</h2>
                    <div class="fs-5 fw-bold text-primary">{{ $nextEnrollment->subject->code }} · {{ $nextEnrollment->subject->name }}</div>
                    <div class="mt-3">{{ $nextClass['starts_at']->format('l, F j') }}</div>
                    <div class="small text-body-secondary">
                        {{ $nextClass['starts_at']->format('g:i A') }}–{{ \Illuminate\Support\Carbon::parse($nextEnrollment->subject->end_time)->format('g:i A') }}
                        · {{ $nextEnrollment->school_year }} / {{ $nextEnrollment->semester }}
                    </div>
                @else
                    <h2 class="h5 fw-semibold mb-2">No upcoming subject</h2>
                    <p class="text-body-secondary mb-0">No active enrollment has a future meeting time scheduled.</p>
                @endif
            </div>
        </section>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-5">
        <section class="card student-profile-panel shadow-sm h-100" id="student-timeline">
            <div class="card-body p-4">
                <div class="small text-uppercase text-body-secondary fw-semibold mb-2">Academic activity</div>
                <h2 class="h5 fw-semibold mb-4">{{ $student->first_name }}'s Timeline</h2>
                @forelse ($enrollments as $enrollment)
                    <div class="student-profile-timeline">
                        <article class="student-profile-timeline-item">
                            <div class="small text-body-secondary mb-1">{{ $enrollment->created_at->format('M j, Y · g:i A') }}</div>
                            <h3 class="h6 fw-semibold mb-1">{{ $enrollment->subject->code }} · {{ $enrollment->subject->name }}</h3>
                            <p class="small text-body-secondary mb-0">
                                {{ ucfirst($enrollment->status) }} · {{ $enrollment->school_year }} / {{ $enrollment->semester }}
                                @if ($enrollment->grade) · Grade {{ $enrollment->grade }} @endif
                            </p>
                        </article>
                    </div>
                @empty
                    <p class="text-body-secondary mb-0">Enrollment activity will appear here when the student is enrolled in a subject.</p>
                @endforelse
            </div>
        </section>
    </div>
    <div class="col-xl-7">
        <section class="card student-profile-panel shadow-sm h-100">
            <div class="card-body p-4">
                <div class="small text-uppercase text-body-secondary fw-semibold mb-2">Academic records</div>
                <h2 class="h5 fw-semibold mb-4">Current and past subjects</h2>
                @forelse ($enrollments->take(5) as $enrollment)
                    <div class="d-flex justify-content-between gap-3 border-top py-3">
                        <div>
                            <div class="fw-semibold">{{ $enrollment->subject->code }} · {{ $enrollment->subject->name }}</div>
                            <div class="small text-body-secondary">{{ $enrollment->school_year }} · {{ $enrollment->semester }} · {{ $enrollment->subject->units }} units</div>
                        </div>
                        <span class="badge rounded-pill text-bg-{{ ['enrolled' => 'primary', 'completed' => 'success', 'dropped' => 'secondary'][$enrollment->status] ?? 'light' }} align-self-start">{{ ucfirst($enrollment->status) }}</span>
                    </div>
                @empty
                    <div class="border-top pt-3 text-body-secondary">No enrollment records yet.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<section class="card student-profile-panel shadow-sm" id="all-enrollments">
    <div class="card-body p-0">
        <div class="p-4 border-bottom">
            <div class="small text-uppercase text-body-secondary fw-semibold mb-2">Student records</div>
            <h2 class="h5 fw-semibold mb-0">All enrollments</h2>
        </div>
        <div class="table-responsive">
            <table class="table student-profile-table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Subject</th><th>Units</th><th>Term</th><th>Status</th><th class="pe-4">Grade</th></tr></thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td class="ps-4"><span class="fw-semibold">{{ $enrollment->subject->code }}</span><span class="d-block small text-body-secondary">{{ $enrollment->subject->name }}</span></td>
                            <td>{{ $enrollment->subject->units }}</td>
                            <td>{{ $enrollment->school_year }} · {{ $enrollment->semester }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ ['enrolled' => 'primary', 'completed' => 'success', 'dropped' => 'secondary'][$enrollment->status] ?? 'light' }}">{{ ucfirst($enrollment->status) }}</span></td>
                            <td class="pe-4">{{ $enrollment->grade ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-body-secondary py-4">Not enrolled in any subject.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
