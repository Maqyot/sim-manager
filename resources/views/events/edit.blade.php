@extends('layouts.app')
@section('title', 'Edit event')
@section('content')
<div class="row justify-content-center"><div class="col-xl-8"><div class="mb-4"><div class="small text-body-secondary mb-2">SIMS / Calendar</div><h1 class="h3 fw-bold">Edit event</h1></div><section class="card shadow-sm"><div class="card-body p-4"><form method="POST" action="{{ route('events.update', $event) }}">@csrf @method('PUT') @include('events._form')</form></div></section></div></div>
@endsection
