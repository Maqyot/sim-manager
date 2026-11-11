@extends('layouts.app')
@section('title', 'Login')
@section('content')
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
                            <input type="radio" class="btn-check" name="account_type" id="account_type_staff" value="staff" @checked(old('account_type', 'staff') === 'staff')>
                            <label class="btn btn-outline-primary" for="account_type_staff">Admin / Staff</label>
                            <input type="radio" class="btn-check" name="account_type" id="account_type_student" value="student" @checked(old('account_type', request('account_type')) === 'student')>
                            <label class="btn btn-outline-primary" for="account_type_student">Student</label>
                        </div>
                        @error('account_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="mb-3" id="staff-email-field">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" class="form-control @error('email') is-invalid @enderror" autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 d-none" id="student-number-field">
                        <label for="student_number" class="form-label">Student number</label>
                        <input id="student_number" type="text" name="student_number" value="{{ old('student_number') }}" autocomplete="username" class="form-control @error('student_number') is-invalid @enderror" disabled>
                        @error('student_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" autocomplete="current-password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center text-body-secondary mt-3 mb-0" id="staff-login-help">Contact the system admin before requesting your credentials.</p>
            </div>
        </div>
    </div>
</div>
<script>
    const accountTypeInputs = document.querySelectorAll('input[name="account_type"]');
    const staffEmailField = document.getElementById('staff-email-field');
    const studentNumberField = document.getElementById('student-number-field');
    const staffEmailInput = document.getElementById('email');
    const studentNumberInput = document.getElementById('student_number');
    const staffLoginHelp = document.getElementById('staff-login-help');

    function updateLoginFields() {
        const isStudent = document.getElementById('account_type_student').checked;
        staffEmailField.classList.toggle('d-none', isStudent);
        studentNumberField.classList.toggle('d-none', !isStudent);
        staffLoginHelp.classList.toggle('d-none', isStudent);
        staffEmailInput.disabled = isStudent;
        studentNumberInput.disabled = !isStudent;
    }

    accountTypeInputs.forEach((input) => input.addEventListener('change', updateLoginFields));
    updateLoginFields();
</script>
@endsection
