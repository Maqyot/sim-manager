@extends('layouts.app')
@section('title', 'Update Enrollment')
@section('content')
<h1 class="h3 mb-3">Update Enrollment</h1>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('enrollments.update', $enrollment) }}" novalidate>@csrf @method('PUT') @include('enrollments._form')</form>
</div></div>
@endsection
