@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Create account</h1>
                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf
                    @foreach ([['name', 'Name', 'text'], ['email', 'Email', 'email'], ['password', 'Password (min 8 characters)', 'password'], ['password_confirmation', 'Confirm password', 'password']] as [$field, $label, $type])
                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                            <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" value="{{ $type === 'password' ? '' : old($field) }}" class="form-control @error($field) is-invalid @enderror">
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <button class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center text-body-secondary mt-3 mb-0">Already registered? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
