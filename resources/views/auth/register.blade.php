@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="auth-card">
        <div class="card shadow">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0">Customer Registration</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Register</button>
                </form>

                <div class="text-center mt-3">
                    <small>Already registered?
                        <a href="{{ route('login') }}">Login here</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection