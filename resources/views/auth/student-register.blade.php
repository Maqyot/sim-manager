@extends('layouts.app')
@section('title', 'Student registration')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="mb-4">
                    <span class="badge text-bg-primary-subtle text-primary mb-2">Student portal</span>
                    <h1 class="h3 mb-2">Student registration</h1>
                    <p class="text-body-secondary mb-0">Submit your details to request a student account. An administrator must approve it before your first login.</p>
                </div>
                <form method="POST" action="{{ route('student.register.store') }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        @foreach ([['student_number', 'Student number', 'text'], ['first_name', 'First name', 'text'], ['last_name', 'Last name', 'text'], ['email', 'Email address', 'email'], ['course', 'Course / program', 'text']] as [$field, $label, $type])
                            <div class="col-md-6">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" value="{{ old($field) }}" class="form-control @error($field) is-invalid @enderror" @if ($field === 'email') autocomplete="email" @endif required>
                                @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endforeach
                        <div class="col-md-6">
                            <label for="year_level" class="form-label">Year level</label>
                            <select id="year_level" name="year_level" class="form-select @error('year_level') is-invalid @enderror" required>
                                <option value="">Choose year level</option>
                                @foreach (range(1, 6) as $year)
                                    <option value="{{ $year }}" @selected((string) old('year_level') === (string) $year)>Year {{ $year }}</option>
                                @endforeach
                            </select>
                            @error('year_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @foreach ([['password', 'Password (at least 8 characters)'], ['password_confirmation', 'Confirm password']] as [$field, $label])
                            <div class="col-md-6">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <input id="{{ $field }}" type="password" name="{{ $field }}" class="form-control @error('password') is-invalid @enderror" autocomplete="{{ $field === 'password' ? 'new-password' : 'new-password' }}" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endforeach
                    </div>
                    <div class="d-grid mt-4">
                        <button class="btn btn-primary">Submit registration</button>
                    </div>
                </form>
                <p class="text-center text-body-secondary mt-3 mb-0">Already registered? <a href="{{ route('login', ['account_type' => 'student']) }}">Student login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection