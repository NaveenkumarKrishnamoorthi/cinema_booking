<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        return view('customer.dashboard', [
            'movies'        => Movie::where('status', 'active')->latest()->take(6)->get(),
            'recentBookings'=> Booking::with('movie')
                ->where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get(),
            'totalBookings' => Booking::where('user_id', Auth::id())->count(),
        ]);
    }
}