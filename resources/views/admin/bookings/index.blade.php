@extends('layouts.app')

@section('title', 'Customer Bookings')

@section('content')
    <h2>Customer Bookings</h2>

    <form method="GET" class="row g-2 my-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control"
                   placeholder="Search customer or movie..."
                   value="{{ request('search') }}">
        </div>
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

    <table class="table table-bordered bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Movie</th>
                <th>Seats</th>
                <th>Total</th>
                <th>Show Time</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>
                        <span class="font-monospace small">{{ $booking->booking_code }}</span>
                    </td>
                    <td>
                        {{ $booking->user->name }}<br>
                        <small class="text-muted">{{ $booking->user->email }}</small>
                    </td>
                    <td>{{ $booking->movie->title }}</td>
                    <td>{{ $booking->seats }}</td>
                    <td>${{ number_format($booking->total_price, 2) }}</td>
                    <td>{{ $booking->show_time->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge bg-{{
                            $booking->status === 'confirmed' ? 'success' :
                            ($booking->status === 'cancelled' ? 'danger' : 'warning')
                        }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.bookings.status', $booking) }}"
                              method="POST" class="d-flex">
                            @csrf @method('PATCH')
                            <select name="status" class="form-select form-select-sm me-1">
                                @foreach(['pending','confirmed','cancelled'] as $s)
                                    <option value="{{ $s }}"
                                        {{ $booking->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $bookings->links() }}
@endsection