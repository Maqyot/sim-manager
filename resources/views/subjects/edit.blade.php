@extends('layouts.app')
@section('title', 'Edit Subject')
@section('content')
<h1 class="h3 mb-3">Edit Subject</h1>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('subjects.update', $subject) }}" novalidate>@csrf @method('PUT') @include('subjects._form')</form>
</div></div>
@endsection
