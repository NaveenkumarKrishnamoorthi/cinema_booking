@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
    <h2>Booking History</h2>

    <table class="table table-bordered bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
                <th>Movie</th>
                <th>Tickets</th>
                <th>Total</th>
                <th>Show Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td><span class="font-monospace small">{{ $booking->booking_code }}</span></td>
                    <td>{{ $booking->movie->title }}</td>
                    <td>{{ $booking->seats }}</td>
                    <td>₹{{ number_format($booking->total_price, 2) }}</td>
                    <td>{{ $booking->show_time->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge bg-{{
                            $booking->status === 'confirmed' ? 'success' :
                            ($booking->status === 'cancelled' ? 'danger' : 'warning')
                        }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('customer.bookings.show', $booking) }}"
                           class="btn btn-sm btn-outline-primary">View</a>

                        @if($booking->status !== 'cancelled')
                            <form action="{{ route('customer.bookings.cancel', $booking) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Cancel this booking?');">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        No bookings yet.
                        <a href="{{ route('customer.movies.index') }}">Browse movies →</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $bookings->links() }}
@endsection