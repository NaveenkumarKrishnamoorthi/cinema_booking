@extends('layouts.app')

@section('title', 'Bookings for My Movies')

@section('content')
    <h2>Bookings for My Movies</h2>

    <form method="GET" class="row g-2 my-3">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Movie</th>
                <th>Seats</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td><span class="font-monospace small">{{ $booking->booking_code }}</span></td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->movie->title }}</td>
                    <td>{{ $booking->seats }}</td>
                    <td>${{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="badge bg-{{
                            $booking->status === 'confirmed' ? 'success' :
                            ($booking->status === 'cancelled' ? 'danger' : 'warning')
                        }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $bookings->links() }}
@endsection