@extends('layouts.app')
@section('title', 'Add Student')
@section('content')
<h1 class="h3 mb-3">Add Student</h1>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('students.store') }}" novalidate>@csrf @include('students._form')</form>
</div></div>
@endsection
