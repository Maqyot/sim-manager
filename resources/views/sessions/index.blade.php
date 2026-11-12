@extends('layouts.app')
@section('title', 'Academic sessions')
@push('styles')
<style>.sessions-heading,.session-card{border-radius:1rem}.sessions-heading{background:linear-gradient(120deg,#173b70,#2864a8);color:#fff}.session-card{border:1px solid var(--bs-border-color)}.session-value{font-size:1.75rem;font-weight:700;letter-spacing:-.04em}</style>
@endpush
@section('content')
<div class="sessions-heading p-4 p-lg-5 mb-4"><div class="small text-white-50 mb-2">SIMS / Academic records</div><h1 class="h2 fw-bold mb-2">Academic sessions</h1><p class="mb-0 text-white-50">Enrollment activity grouped by school year and semester.</p></div>
<div class="row g-3">
@forelse($sessions as $session)
<div class="col-md-6 col-xl-4"><a class="card session-card shadow-sm h-100 text-decoration-none text-body" href="{{ route('enrollments.index', ['school_year' => $session->school_year, 'semester' => $session->semester]) }}"><div class="card-body p-4"><span class="badge rounded-pill text-bg-primary-subtle text-primary">Academic session</span><h2 class="h5 fw-semibold mt-3">{{ $session->school_year }} / {{ $session->semester }} semester</h2><div class="session-value">{{ number_format($session->enrollment_count) }}</div><div class="small text-body-secondary">enrollment record{{ $session->enrollment_count === 1 ? '' : 's' }}</div><span class="small d-inline-block mt-3">View enrollments</span></div></a></div>
@empty
<div class="col-12"><div class="card session-card"><div class="card-body text-center py-5"><h2 class="h5">No academic sessions yet</h2><p class="text-body-secondary mb-0">Sessions appear when students enroll in a term.</p></div></div></div>
@endforelse
</div>
@endsection
