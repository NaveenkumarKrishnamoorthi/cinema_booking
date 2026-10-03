@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="hero text-center">
        <h1 class="display-4">Welcome to MovieBooking</h1>
        <p class="lead">Book your favorite movies in seconds.</p>

        @guest
            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg">Register as Customer</a>
            </div>
        @endguest

        @auth
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}"
               class="btn btn-success btn-lg mt-3">
                Go to {{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }} Dashboard
            </a>
        @endauth
    </div>
@endsection