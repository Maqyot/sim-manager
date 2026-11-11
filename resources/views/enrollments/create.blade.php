@extends('layouts.app')
@section('title', 'Enroll Student')
@section('content')
<h1 class="h3 mb-3">Enroll Student</h1>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('enrollments.store') }}" novalidate>@csrf @include('enrollments._form')</form>
</div></div>
@endsection
