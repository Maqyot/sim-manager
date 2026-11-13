@extends('layouts.app')
@section('title', 'Login')
@section('content')
@php($loginType = old('account_type', request('account_type', 'staff')))
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Sign in</h1>
                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <fieldset class="mb-3">
                        <legend class="form-label fs-6">Choose your login</legend>
                        <div class="btn-group w-100" role="group" aria-label="Login type">
                            <a class="btn {{ $loginType === 'staff' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('login', ['account_type' => 'staff']) }}" @if ($loginType === 'staff') aria-current="true" @endif>Admin / Staff</a>
                            <a class="btn {{ $loginType === 'student' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('login', ['account_type' => 'student']) }}" @if ($loginType === 'student') aria-current="true" @endif>Student</a>
                        </div>
                        <input type="hidden" name="account_type" value="{{ $loginType }}">
                        @error('account_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="mb-3 {{ $loginType === 'student' ? 'd-none' : '' }}" id="staff-email-field">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" class="form-control @error('email') is-invalid @enderror" autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 {{ $loginType === 'student' ? '' : 'd-none' }}" id="student-number-field">
                        <label for="student_number" class="form-label">Student number</label>
                        <input id="student_number" type="text" name="student_number" value="{{ old('student_number') }}" autocomplete="username" class="form-control @error('student_number') is-invalid @enderror" {{ $loginType === 'student' ? 'autofocus' : 'disabled' }}>
                        @error('student_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
                        @if ($loginType === 'student')<div class="form-text">Use the password the admin was given</div>@endif
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                @if ($loginType === 'student')
                    <div class="text-center mt-3" id="student-login-help">
                        <p class="text-body-secondary mb-2">Your account needs administrator approval before you can sign in.</p>
                        <a class="btn btn-outline-success" href="{{ route('student.register') }}">Register as a student</a>
                    </div>
                @else
                    <p class="text-center text-body-secondary mt-3 mb-0" id="staff-login-help">Contact the system admin before requesting your credentials.</p>
                    <p class="text-center mt-3 mb-0">New student? <a href="{{ route('student.register') }}">Register here</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
