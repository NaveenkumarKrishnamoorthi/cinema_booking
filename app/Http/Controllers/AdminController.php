<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalMovies'    => Movie::count(),
            'activeMovies'   => Movie::where('status', 'active')->count(),
            'totalBookings'  => Booking::count(),
            'totalRevenue'   => Booking::where('status', 'confirmed')->sum('total_price'),
            'totalUsers'     => User::where('role', 'customer')->count(),
            'recentBookings' => Booking::with(['user', 'movie'])->latest()->take(5)->get(),
        ]);
    }
}