@extends('layouts.app')
@section('title', 'Enrollments')
@push('styles')
<style>
    .enrollments-heading,
    .enrollments-stat,
    .enrollments-panel {
        border-radius: 1rem;
    }

    .enrollments-heading {
        background: linear-gradient(120deg, #173b70, #2864a8);
        color: #fff;
    }

    .enrollments-stat,
    .enrollments-panel {
        border: 1px solid var(--bs-border-color);
    }

    .enrollments-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -.04em;
    }

    .enrollments-table th {
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
    $pageStatusCounts = $enrollments->getCollection()->countBy('status');
@endphp
<div class="enrollments-heading p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="small text-white-50 mb-2">SIMS / Academic records</div>
            <h1 class="h2 fw-bold mb-2">Enrollment management</h1>
            <p class="mb-0 text-white-50">Review student registrations, terms, and enrollment status.</p>
        </div>
        <a href="{{ route('enrollments.create') }}" class="btn btn-light fw-semibold">Enroll student</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card enrollments-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
            <div class="small text-body-secondary mb-2">Total enrollments</div>
            <div class="enrollments-stat-value">{{ number_format($enrollments->total()) }}</div>
            <div class="small text-body-secondary mt-1">All recorded enrollments</div>
        </div></div>
    </div>
    @foreach ([['Enrolled', 'enrolled', 'text-primary'], ['Completed', 'completed', 'text-success'], ['Dropped', 'dropped', 'text-secondary']] as [$label, $status, $color])
        <div class="col-sm-6 col-xl-3">
            <div class="card enrollments-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
                <div class="small text-body-secondary mb-2">{{ $label }} <span class="text-body-tertiary">(this page)</span></div>
                <div class="enrollments-stat-value {{ $color }}">{{ number_format($pageStatusCounts->get($status, 0)) }}</div>
                <div class="small text-body-secondary mt-1">Of {{ number_format($enrollments->count()) }} displayed records</div>
            </div></div>
        </div>
    @endforeach
</div>

<section class="card enrollments-panel shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 p-4 border-bottom">
            <div>
                <h2 class="h5 fw-semibold mb-1">All enrollments</h2>
                <p class="small text-body-secondary mb-0">Student, subject, and term details.</p>
            </div>
            <span class="small text-body-secondary">{{ number_format($enrollments->firstItem() ?? 0) }}–{{ number_format($enrollments->lastItem() ?? 0) }} of {{ number_format($enrollments->total()) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table enrollments-table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Student</th><th>Subject</th><th>Term</th><th>Status</th><th>Grade</th><th class="text-end pe-4">Actions</th></tr></thead>
                <tbody>
                    @forelse ($enrollments as $e)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $e->student->full_name }}</td>
                            <td><span class="d-block fw-medium">{{ $e->subject->code }}</span><span class="small text-body-secondary">{{ $e->subject->name }}</span></td>
                            <td>{{ $e->school_year }} · {{ $e->semester }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ ['enrolled' => 'primary', 'completed' => 'success', 'dropped' => 'secondary'][$e->status] ?? 'light' }}">{{ ucfirst($e->status) }}</span></td>
                            <td>{{ $e->grade ?? '—' }}</td>
                            <td class="text-end text-nowrap pe-4">
                                <a href="{{ route('enrollments.edit', $e) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @if (auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('enrollments.destroy', $e) }}" class="d-inline" onsubmit="return confirm('Remove this enrollment?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">
                            <div class="fw-semibold mb-1">No enrollments yet</div>
                            <div class="small text-body-secondary">Enrollments will appear here when students are registered in subjects.</div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            @include('partials.pager', ['paginator' => $enrollments])
        </div>
    </div>
</section>
@endsection
