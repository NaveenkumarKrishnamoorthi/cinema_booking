<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // List all movies
    public function index()
    {
        $movies = Movie::latest()->paginate(10);
        return view('admin.movies.index', compact('movies'));
    }

    // Show create form
    public function create()
    {
        return view('admin.movies.create');
    }

    // Store new movie
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'genre'           => 'required|string|max:100',
            'duration'        => 'required|integer|min:1',
            'release_date'    => 'required|date',
            'price'           => 'required|numeric|min:0',
            'total_seats'     => 'required|integer|min:1',
            'poster'          => 'nullable|string|max:500',
            'status'          => 'required|in:active,inactive',
        ]);

        $data['available_seats'] = $data['total_seats'];

        Movie::create($data);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie added successfully.');
    }

    // Show edit form
    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    // Update movie
    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'genre'           => 'required|string|max:100',
            'duration'        => 'required|integer|min:1',
            'release_date'    => 'required|date',
            'price'           => 'required|numeric|min:0',
            'total_seats'     => 'required|integer|min:1',
            'poster'          => 'nullable|string|max:500',
            'status'          => 'required|in:active,inactive',
        ]);

        // Recalculate available seats if total seats changed
        if ($data['total_seats'] !== $movie->total_seats) {
            $bookedSeats = $movie->total_seats - $movie->available_seats;
            $data['available_seats'] = max(0, $data['total_seats'] - $bookedSeats);
        }

        $movie->update($data);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    // Delete movie
    public function destroy(Movie $movie)
    {
        // Prevent deleting movies with existing bookings
        if ($movie->bookings()->exists()) {
            return back()->with('error', 'Cannot delete a movie that has bookings. Deactivate it instead.');
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie deleted successfully.');
    }

    // Toggle active/inactive
    public function toggleStatus(Movie $movie)
    {
        $movie->update([
            'status' => $movie->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Movie status updated.');
    }
}