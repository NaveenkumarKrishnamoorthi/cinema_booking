@extends('layouts.app')

@section('title', 'Organizer Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"> Organizer Dashboard</h2>
            <small class="text-muted">Welcome, {{ auth()->user()->name }}</small>
        </div>
        <a href="{{ route('organizer.movies.create') }}" class="btn btn-primary">
            + Add Movie
        </a>
    </div>

    {{-- Quick action cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="{{ route('organizer.movies.index') }}" class="text-decoration-none">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title">🎬 Manage My Movies</h5>
                        <p class="card-text small mb-0">View, edit, and manage your movie listings</p>
                    </div>
                    <div class="card-footer bg-transparent border-light">
                        <small class="text-white">Go →</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('organizer.bookings.index') }}" class="text-decoration-none">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <h5 class="card-title">📋 View Bookings</h5>
                        <p class="card-text small mb-0">See all ticket bookings for your movies</p>
                    </div>
                    <div class="card-footer bg-transparent border-light">
                        <small class="text-white">Go →</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('organizer.movies.create') }}" class="text-decoration-none">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <h5 class="card-title">➕ Add New Movie</h5>
                        <p class="card-text small mb-0">Create a new movie listing</p>
                    </div>
                    <div class="card-footer bg-transparent border-light">
                        <small class="text-white">Go →</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h6>My Movies</h6>
                    <p class="display-6 mb-0">{{ $totalMovies }}</p>
                    <small>{{ $activeMovies }} active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h6>Total Bookings</h6>
                    <p class="display-6 mb-0">{{ $totalBookings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h6>Revenue</h6>
                    <p class="display-6 mb-0">₹{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h6>Recent Bookings</h6>
                    <p class="display-6 mb-0">{{ $recentBookings->count() }}</p>
                    <small>last 5 shown below</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent bookings --}}
    <h4 class="mt-4">Recent Bookings</h4>
    <table class="table table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Customer</th>
                <th>Movie</th>
                <th>Seats</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->movie->title }}</td>
                    <td>{{ $booking->seats }}</td>
                    <td>₹{{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="badge bg-{{
                            $booking->status === 'confirmed' ? 'success' :
                            ($booking->status === 'cancelled' ? 'danger' : 'warning')
                        }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        No bookings yet.
                        <a href="{{ route('organizer.movies.create') }}">Add a movie</a> to get started.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection