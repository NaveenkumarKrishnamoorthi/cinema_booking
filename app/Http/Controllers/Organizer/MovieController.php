<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('organizer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('organizer.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('organizer.movies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'genre'        => 'required|string|max:100',
            'duration'     => 'required|integer|min:1',
            'release_date' => 'required|date',
            'price'        => 'required|numeric|min:0',
            'total_seats'  => 'required|integer|min:1',
            'poster'       => 'nullable|string|max:500',
            'status'       => 'required|in:active,inactive',
        ]);

        $data['organizer_id']    = Auth::id();
        $data['available_seats'] = $data['total_seats'];

        Movie::create($data);

        return redirect()->route('organizer.movies.index')
            ->with('success', 'Movie added successfully.');
    }

    public function edit(Movie $movie)
    {
        $this->authorizeOwnership($movie);

        return view('organizer.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $this->authorizeOwnership($movie);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'genre'        => 'required|string|max:100',
            'duration'     => 'required|integer|min:1',
            'release_date' => 'required|date',
            'price'        => 'required|numeric|min:0',
            'total_seats'  => 'required|integer|min:1',
            'poster'       => 'nullable|string|max:500',
            'status'       => 'required|in:active,inactive',
        ]);

        if ($data['total_seats'] !== $movie->total_seats) {
            $bookedSeats = $movie->total_seats - $movie->available_seats;
            $data['available_seats'] = max(0, $data['total_seats'] - $bookedSeats);
        }

        $movie->update($data);

        return redirect()->route('organizer.movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    public function destroy(Movie $movie)
    {
        $this->authorizeOwnership($movie);

        if ($movie->bookings()->exists()) {
            return back()->with('error', 'Cannot delete a movie with bookings. Deactivate it instead.');
        }

        $movie->delete();

        return redirect()->route('organizer.movies.index')
            ->with('success', 'Movie deleted successfully.');
    }

    public function toggleStatus(Movie $movie)
    {
        $this->authorizeOwnership($movie);

        $movie->update([
            'status' => $movie->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Movie status updated.');
    }

   
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'movie'])
            ->whereHas('movie', fn($q) => $q->where('organizer_id', Auth::id()))
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15);

        return view('organizer.bookings.index', compact('bookings'));
    }

   
    private function authorizeOwnership(Movie $movie): void
    {
        if ($movie->organizer_id !== Auth::id()) {
            abort(403, 'You do not have permission to manage this movie.');
        }
    }
}