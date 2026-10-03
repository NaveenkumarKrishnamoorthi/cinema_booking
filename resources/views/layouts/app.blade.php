<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Movie Booking')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .auth-card { max-width: 480px; margin: 60px auto; }
        .hero { background: linear-gradient(135deg, #141e30, #243b55); color: #fff; padding: 80px 20px; border-radius: 12px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> 
    <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}">🎬 MovieBooking</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">  
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            @auth
                <ul class="navbar-nav me-auto">
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.movies.index') }}">Movies</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.movies.index') }}">Movies</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.bookings.index') }}">My Bookings</a></li>
                    @endif
                </ul>
            @endauth

            <div class="ms-auto d-flex align-items-center">
                @auth
                    <span class="text-white me-3">
                        {{ auth()->user()->name }} ({{ auth()->user()->role }})
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-warning btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>