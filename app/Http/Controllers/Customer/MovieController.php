<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('customer.movies.index', compact('movies'));
    }

    public function show(Movie $movie)
    {
        // Only show active movies to customers
        if (!$movie->isActive()) {
            abort(404);
        }

        return view('customer.movies.show', compact('movie'));
    }
}