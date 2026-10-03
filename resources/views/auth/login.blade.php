@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="auth-card">
        <div class="card shadow">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <div class="text-center mt-3">
                    <small>Don't have an account?
                        <a href="{{ route('register') }}">Register here</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection