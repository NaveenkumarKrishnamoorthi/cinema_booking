<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCancellation;

class BookingController extends Controller
{
    /**
     * List the customer's bookings (booking history).
     */
    public function index()
    {
        $bookings = Booking::with('movie')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Show the booking form for a movie.
     */
    public function create(Movie $movie)
    {
        if (!$movie->isActive()) {
            return redirect()->route('customer.movies.index')
                ->with('error', 'This movie is not available for booking.');
        }

        if ($movie->available_seats < 1) {
            return redirect()->route('customer.movies.show', $movie)
                ->with('error', 'Sorry, this movie is sold out.');
        }

        return view('customer.bookings.create', compact('movie'));
    }

    /**
     * Store a new booking, send the confirmation email,
     * and redirect to the confirmation page.
     */
    public function store(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'seats'     => 'required|integer|min:1|max:10',
            'show_time' => 'required|date|after:now',
        ]);

        if (!$movie->isActive()) {
            return back()->with('error', 'This movie is not available for booking.');
        }

        try {
            $booking = DB::transaction(function () use ($data, $movie) {
                /** @var Movie $freshMovie */
                $freshMovie = Movie::lockForUpdate()->find($movie->id);

                if ($freshMovie->available_seats < $data['seats']) {
                    throw new \Exception('Not enough available seats.');
                }

                // Generate booking ID, calculate total, save, reduce seats
                $booking = Booking::create([
                    'booking_code' => Booking::generateBookingCode(),
                    'user_id'      => Auth::id(),
                    'movie_id'     => $freshMovie->id,
                    'seats'        => $data['seats'],
                    'total_price'  => $freshMovie->price * $data['seats'],
                    'show_time'    => $data['show_time'],
                    'status'       => 'confirmed',
                ]);

                $freshMovie->decrement('available_seats', $data['seats']);

                return $booking;
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        // Send confirmation email (don't fail the booking if mail fails)
        try {
            Mail::to($booking->user->email)
                ->send(new BookingConfirmation($booking->load(['user', 'movie'])));
        } catch (\Throwable $e) {
            Log::error('Booking confirmation mail failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with('success', 'Booking confirmed! A confirmation email has been sent to ' . $booking->user->email);
    }

    /**
     * Show a single booking (confirmation / detail page).
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load('movie');

        return view('customer.bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking and release its seats.
     */
    public function cancel(Booking $booking)
{
    if ($booking->user_id !== Auth::id()) {
        abort(403);
    }

    if ($booking->status === 'cancelled') {
        return back()->with('error', 'This booking is already cancelled.');
    }

    try {
        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);
            $booking->movie->increment('available_seats', $booking->seats);
        });
    } catch (\Throwable $e) {
        Log::error('Booking cancellation failed: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while cancelling. Please try again.');
    }

    // Send cancellation email (don't fail cancellation if mail fails)
    $mailStatus = 'sent';
    $mailError  = null;

    try {
        Mail::to($booking->user->email)
            ->send(new BookingCancellation($booking->load(['user', 'movie'])));
    } catch (\Throwable $e) {
        $mailStatus = 'failed';
        $mailError  = $e->getMessage();
        Log::error('Booking cancellation mail failed: ' . $e->getMessage());
        Log::error($e);
    }

    if ($mailStatus === 'sent') {
        return back()->with('success', 'Booking cancelled. Seats released. A cancellation email has been sent to ' . $booking->user->email . '.');
    }

    return back()->with('error', 'Booking cancelled and seats released, but the cancellation email could not be sent: ' . $mailError);
}
};