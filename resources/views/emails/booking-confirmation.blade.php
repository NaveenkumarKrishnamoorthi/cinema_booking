@component('mail::message')
# 🎬 Booking Confirmed!

Hi **{{ $user->name }}**,

Thank you for booking with **MovieBooking**. Your tickets are confirmed. Here are your details:

@component('mail::table')
| | |
|:--|:--|
| **Booking ID** | `{{ $booking->booking_code }}` |
| **Movie** | {{ $movie->title }} |
| **Genre** | {{ $movie->genre }} |
| **Duration** | {{ $movie->duration }} minutes |
| **Show Time** | {{ $booking->show_time->format('M d, Y - h:i A') }} |
| **Tickets** | {{ $booking->seats }} |
| **Price per Ticket** | ₹{{ number_format($movie->price, 2) }} |
| **Total Amount** | **₹{{ number_format($booking->total_price, 2) }}** |
| **Status** | {{ ucfirst($booking->status) }} |
@endcomponent

Please show this email or your **Booking ID** at the cinema entrance.

@component('mail::button', ['url' => route('customer.bookings.show', $booking), 'color' => 'green'])
View Booking Details
@endcomponent

Enjoy the movie!

Thanks,<br>
{{ config('app.name') }}
@endcomponent