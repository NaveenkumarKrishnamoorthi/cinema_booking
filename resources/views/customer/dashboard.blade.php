@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
    <h2>Welcome, {{ auth()->user()->name }} 🎬</h2>
    <p class="text-muted">Browse movies and view your bookings below.</p>

    @if(auth()->user()->hasPendingOrganizerRequest())
    <a href="{{ route('customer.organizer-request.status') }}" class="btn btn-warning">
         Organizer Request Pending
    </a>
@else
    <a href="{{ route('customer.organizer-request.create') }}" class="btn btn-outline-warning">
         Request to Become Organizer
    </a>
@endif

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6>My Bookings</h6>
                    <p class="display-6 mb-0">{{ $totalBookings }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <h4>Latest Movies</h4>
        <a href="{{ route('customer.movies.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
    </div>

    <div class="row">
        @forelse($movies as $movie)
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    @if($movie->poster)
                        <img src="{{ $movie->poster }}" class="card-img-top" style="height:200px;object-fit:cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie->title }}</h5>
                        <p class="card-text small text-muted">{{ Str::limit($movie->description, 120) }}</p>
                        <p class="mb-1"><strong>Price:</strong> ₹{{ number_format($movie->price, 2) }}</p>
                        <p class="mb-2"><strong>Seats left:</strong> {{ $movie->available_seats }}</p>
                        <a href="{{ route('customer.movies.show', $movie) }}" class="btn btn-sm btn-primary">View</a>
                        <a href="{{ route('customer.bookings.create', $movie) }}" class="btn btn-sm btn-success">Book</a>
                    </div>
                </div>
            </div>
        @empty
            <p>No movies available yet.</p>
        @endforelse
    </div>

    <h4 class="mt-4">Recent Bookings</h4>
    <table class="table table-striped">
        <thead>
            <tr><th>Movie</th><th>Seats</th><th>Total</th><th>Show Time</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->movie->title }}</td>
                    <td>{{ $booking->seats }}</td>
                    <td>₹{{ number_format($booking->total_price, 2) }}</td>
                    <td>{{ $booking->show_time->format('M d, Y H:i') }}</td>
                    <td><span class="badge bg-secondary">{{ $booking->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">You have no bookings yet.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection