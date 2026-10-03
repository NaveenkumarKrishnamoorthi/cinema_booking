@extends('layouts.app')

@section('title', 'Booking Confirmation')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center py-3">
                    <h3 class="mb-0">✅ Booking Confirmed</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <p class="text-muted mb-1">Your Booking ID</p>
                        <h2 class="text-primary font-monospace">{{ $booking->booking_code }}</h2>
                        <small class="text-muted">
                            Keep this ID handy for check-in.
                            A confirmation email was sent to
                            <strong>{{ $booking->user->email }}</strong>.
                        </small>
                    </div>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="35%">Movie</th>
                                <td>{{ $booking->movie->title }}</td>
                            </tr>
                            <tr>
                                <th>Genre</th>
                                <td>{{ $booking->movie->genre }}</td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td>{{ $booking->movie->duration }} minutes</td>
                            </tr>
                            <tr>
                                <th>Show Time</th>
                                <td>{{ $booking->show_time->format('M d, Y - h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Tickets</th>
                                <td>{{ $booking->seats }}</td>
                            </tr>
                            <tr>
                                <th>Price per Ticket</th>
                                <td>₹{{ number_format($booking->movie->price, 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <th>Total Amount</th>
                                <td><strong>₹{{ number_format($booking->total_price, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{
                                        $booking->status === 'confirmed' ? 'success' :
                                        ($booking->status === 'cancelled' ? 'danger' : 'warning')
                                    }}">{{ ucfirst($booking->status) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-primary">
                            ← My Bookings
                        </a>

                        @if($booking->status !== 'cancelled')
                            <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST"
                                  onsubmit="return confirm('Cancel this booking?');">
                                @csrf @method('PATCH')
                                <button class="btn btn-outline-danger">Cancel Booking</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection