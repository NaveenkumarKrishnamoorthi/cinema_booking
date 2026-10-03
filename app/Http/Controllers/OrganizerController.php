<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        $organizerId = Auth::id();

        return view('organizer.dashboard', [
            'totalMovies'    => Movie::where('organizer_id', $organizerId)->count(),
            'activeMovies'   => Movie::where('organizer_id', $organizerId)
                                     ->where('status', 'active')
                                     ->count(),
            'totalBookings'  => Booking::whereHas('movie', fn($q) => $q->where('organizer_id', $organizerId))
                                       ->count(),
            'totalRevenue'   => Booking::whereHas('movie', fn($q) => $q->where('organizer_id', $organizerId))
                                       ->where('status', 'confirmed')
                                       ->sum('total_price'),
            'recentBookings' => Booking::with(['user', 'movie'])
                                       ->whereHas('movie', fn($q) => $q->where('organizer_id', $organizerId))
                                       ->latest()
                                       ->take(5)
                                       ->get(),
        ]);
    }
}