@extends('layouts.app')
@section('title', 'Add event')
@section('content')
<div class="row justify-content-center"><div class="col-xl-8"><div class="mb-4"><div class="small text-body-secondary mb-2">SIMS / Calendar</div><h1 class="h3 fw-bold">Add event</h1><p class="text-body-secondary">Post an event for students and staff.</p></div><section class="card shadow-sm"><div class="card-body p-4"><form method="POST" action="{{ route('events.store') }}">@csrf @php($event = null) @include('events._form')</form></div></section></div></div>
@endsection
