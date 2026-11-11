@extends('layouts.app')
@section('title', 'Subjects')
@push('styles')
<style>
    .subjects-heading,
    .subjects-stat,
    .subjects-panel {
        border-radius: 1rem;
    }

    .subjects-heading {
        background: linear-gradient(120deg, #173b70, #2864a8);
        color: #fff;
    }

    .subjects-stat,
    .subjects-panel {
        border: 1px solid var(--bs-border-color);
    }

    .subjects-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -.04em;
    }

    .subjects-table th {
        color: var(--bs-secondary-color);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
</style>
@endpush
@section('content')
<div class="subjects-heading p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="small text-white-50 mb-2">SIMS / Academic records</div>
            <h1 class="h2 fw-bold mb-2">Subject catalogue</h1>
            <p class="mb-0 text-white-50">Browse subjects and see their enrollment activity.</p>
        </div>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('subjects.create') }}" class="btn btn-light fw-semibold">Add subject</a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="card subjects-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
            <div class="small text-body-secondary mb-2">Total subjects</div>
            <div class="subjects-stat-value">{{ number_format($subjects->total()) }}</div>
            <div class="small text-body-secondary mt-1">In the subject catalogue</div>
        </div></div>
    </div>
    <div class="col-sm-6">
        <div class="card subjects-stat shadow-sm h-100"><div class="card-body p-3 p-lg-4">
            <div class="small text-body-secondary mb-2">Units on this page</div>
            <div class="subjects-stat-value">{{ number_format($subjects->getCollection()->sum('units')) }}</div>
            <div class="small text-body-secondary mt-1">Across {{ number_format($subjects->count()) }} displayed {{ \Illuminate\Support\Str::plural('subject', $subjects->count()) }}</div>
        </div></div>
    </div>
</div>

<section class="card subjects-panel shadow-sm">
    <div class="card-body p-0">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 p-4 border-bottom">
            <div>
                <h2 class="h5 fw-semibold mb-1">All subjects</h2>
                <p class="small text-body-secondary mb-0">Subject details and enrolled student counts.</p>
            </div>
            <span class="small text-body-secondary">{{ number_format($subjects->firstItem() ?? 0) }}–{{ number_format($subjects->lastItem() ?? 0) }} of {{ number_format($subjects->total()) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table subjects-table table-hover align-middle mb-0">
                <thead><tr><th class="ps-4">Subject</th><th>Units</th><th>Schedule</th><th>Enrolled</th><th class="text-end pe-4">Actions</th></tr></thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td class="ps-4">
                                <span class="d-block fw-semibold">{{ $subject->name }}</span>
                                <span class="small text-primary fw-semibold">{{ $subject->code }}</span>
                            </td>
                            <td>{{ $subject->units }}</td>
                            <td>
                                @if ($subject->meeting_days && $subject->start_time && $subject->end_time)
                                    <span class="d-block">{{ implode(', ', $subject->meeting_days) }}</span>
                                    <span class="small text-body-secondary">{{ \Illuminate\Support\Carbon::parse($subject->start_time)->format('g:i A') }}–{{ \Illuminate\Support\Carbon::parse($subject->end_time)->format('g:i A') }}</span>
                                @else
                                    <span class="text-body-secondary">Not scheduled</span>
                                @endif
                            </td>
                            <td><span class="badge rounded-pill text-bg-primary-subtle text-primary">{{ number_format($subject->enrollments_count) }}</span></td>
                            <td class="text-end text-nowrap pe-4">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('subjects.destroy', $subject) }}" class="d-inline" onsubmit="return confirm('Delete this subject and its enrollments?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @else
                                    <span class="text-body-secondary small">View only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5">
                            <div class="fw-semibold mb-1">No subjects yet</div>
                            <div class="small text-body-secondary">Subjects will appear here when they are added.</div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            @include('partials.pager', ['paginator' => $subjects])
        </div>
    </div>
</section>
@endsection
