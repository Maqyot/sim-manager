@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="small text-body-secondary mb-2">Account settings</div>
                    <h1 class="h3 mb-2">Change password</h1>
                    <p class="text-body-secondary mb-0">Enter your current password, then choose a new one.</p>
                </div>
                <form method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current password</label>
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New password</label>
                        <input id="password" type="password" name="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="form-control">
                    </div>
                    <button class="btn btn-primary">Update password</button>
                    <a href="{{ auth()->user()->isStudent() ? route('student.dashboard') : route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
