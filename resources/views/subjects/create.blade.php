@extends('layouts.app')
@section('title', 'Add Subject')
@section('content')
<h1 class="h3 mb-3">Add Subject</h1>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('subjects.store') }}" novalidate>@csrf @include('subjects._form')</form>
</div></div>
@endsection
