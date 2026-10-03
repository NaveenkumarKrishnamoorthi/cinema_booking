@component('mail::message')
# Booking Cancelled

Hi **{{ $user->name }}**,

Your booking with **MovieBooking** has been cancelled. Your seats have been released. Here are the details:

@component('mail::table')
| | |
|:--|:--|
| **Booking ID** | `{{ $booking->booking_code }}` |
| **Movie** | {{ $movie->title }} |
| **Show Time** | {{ $booking->show_time->format('M d, Y - h:i A') }} |
| **Tickets Cancelled** | {{ $booking->seats }} |
| **Amount Refunded** | **₹{{ number_format($booking->total_price, 2) }}** |
| **Status** | Cancelled |
@endcomponent

If you'd like to book again, browse our latest movies:

@component('mail::button', ['url' => route('customer.movies.index'), 'color' => 'primary'])
Browse Movies
@endcomponent

We hope to see you again soon!

Thanks,<br>
{{ config('app.name') }}
@endcomponent