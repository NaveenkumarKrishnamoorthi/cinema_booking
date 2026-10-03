@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h2>Admin Dashboard</h2>
        <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">+ Add Movie</a>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h6>Total Movies</h6>
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
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h6>Customers</h6>
                    <p class="display-6 mb-0">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Recent Bookings</h4>
    <table class="table table-striped">
        <thead>
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
                    <td><span class="badge bg-secondary">{{ $booking->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No bookings yet</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection