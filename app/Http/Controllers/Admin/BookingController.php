<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'movie'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                                                 ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('movie', fn($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $old = $booking->status;
        $new = $request->status;

        // If cancelling a previously confirmed booking, return seats
        if ($old !== 'cancelled' && $new === 'cancelled') {
            $booking->movie->increment('available_seats', $booking->seats);
        }

        // If reactivating a cancelled booking, take seats back (if available)
        if ($old === 'cancelled' && $new !== 'cancelled') {
            if (!$booking->movie->hasSeats($booking->seats)) {
                return back()->with('error', 'Not enough available seats to reactivate this booking.');
            }
            $booking->movie->decrement('available_seats', $booking->seats);
        }

        $booking->update(['status' => $new]);

        return back()->with('success', 'Booking status updated.');
    }
}