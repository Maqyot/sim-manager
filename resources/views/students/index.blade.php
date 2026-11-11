@extends('layouts.app')
@section('title', 'Students')
@push('styles')
<style>
    .students-heading {
        background: linear-gradient(120deg, #173b70, #2864a8);
        border-radius: 1rem;
        color: #fff;
    }

    .students-panel,
    .students-stat {
        border: 1px solid var(--bs-border-color);
        border-radius: 1rem;
    }

    .students-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -.04em;
    }

    .students-table th {
        color: var(--bs-secondary-color);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .student-number {
        color: var(--bs-primary);
        font-size: .8rem;
        font-weight: 600;
        white-space: nowrap;
    }
</style>
@endpush
@section('content')
<div class="students-heading p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="small text-white-50 mb-2">SIMS / Academic records</div>
            <h1 class="h2 fw-bold mb-2">Student directory</h1>
            <p class="mb-0 text-white-50">Browse and manage student records in one place.</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-light fw-semibold">Add student</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card students-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
            <div class="small text-body-secondary mb-2">Matching records</div>
            <div class="students-stat-value">{{ number_format($students->total()) }}</div>
            <div class="small text-body-secondary mt-1">Students matching this search</div>
        </div></div>
    </div>
    <div class="col-sm-4">
        <div class="card students-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
            <div class="small text-body-secondary mb-2">On this page</div>
            <div class="students-stat-value">{{ number_format($students->count()) }}</div>
            <div class="small text-body-secondary mt-1">Records in the current view</div>
        </div></div>
    </div>
    <div class="col-sm-4">
        <div class="card students-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
            <div class="small text-body-secondary mb-2">Courses represented</div>
            <div class="students-stat-value">{{ number_format($students->getCollection()->pluck('course')->unique()->count()) }}</div>
            <div class="small text-body-secondary mt-1">Across records on this page</div>
        </div></div>
    </div>
</div>

<section class="card students-panel shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 p-4 border-bottom">
            <div>
                <h2 class="h5 fw-semibold mb-1">All students</h2>
                <p class="small text-body-secondary mb-0">Search by student number, name, or email.</p>
            </div>
            <form method="GET" role="search" class="d-flex gap-2 w-100 w-lg-auto" style="max-width: 31rem">
                <label for="student-search" class="visually-hidden">Search students</label>
                <input id="student-search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search students...">
                <button class="btn btn-outline-primary">Search</button>
                @if (request()->filled('q'))
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table students-table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Student</th><th>Email</th><th>Course</th><th>Year</th><th>Subjects</th><th class="text-end pe-4">Actions</th></tr></thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('students.show', $student) }}" class="d-block fw-semibold text-body text-decoration-none">{{ $student->last_name }}, {{ $student->first_name }}</a>
                                <span class="student-number">{{ $student->student_number }}</span>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td><span class="badge rounded-pill text-bg-light border">{{ $student->course }}</span></td>
                            <td>{{ $student->year_level }}</td>
                            <td><span class="badge rounded-pill text-bg-primary-subtle text-primary">{{ $student->enrollments_count }}</span></td>
                            <td class="text-end text-nowrap pe-4">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('students.destroy', $student) }}" class="d-inline" onsubmit="return confirm('Delete this student and all enrollments?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">
                            <div class="fw-semibold mb-1">{{ request()->filled('q') ? 'No matching students' : 'No students yet' }}</div>
                            <div class="small text-body-secondary">{{ request()->filled('q') ? 'Try a different search term.' : 'Add a student to start building your directory.' }}</div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            @include('partials.pager', ['paginator' => $students])
        </div>
    </div>
</section>
@endsection
