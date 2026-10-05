<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;

class MovieController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Movie::with('organizer')->latest();


        if ($request->filled('owner')) {
            if ($request->owner === 'admin') {
                $query->whereNull('organizer_id');
            } elseif ($request->owner === 'organizer') {
                $query->whereNotNull('organizer_id');
            }
        }

        if ($request->filled('organizer_id')) {
            $query->where('organizer_id', $request->organizer_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $movies     = $query->paginate(12)->withQueryString();
        $organizers = User::where('role', 'organizer')->orderBy('name')->get();

        return view('admin.movies.index', compact('movies', 'organizers'));
    }

    
    public function create()
    {
        $organizers = User::where('role', 'organizer')->orderBy('name')->get();

        return view('admin.movies.create', compact('organizers'));
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
            'organizer_id' => 'nullable|exists:users,id',
        ]);

        
        if (!empty($data['organizer_id'])) {
            $target = User::find($data['organizer_id']);
            if (!$target || $target->role !== 'organizer') {
                return back()
                    ->withErrors(['organizer_id' => 'Selected user is not an organizer.'])
                    ->withInput();
            }
        }

        $data['available_seats'] = $data['total_seats'];

        Movie::create($data);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie created successfully.');
    }

    
    public function edit(Movie $movie)
    {
        $organizers = User::where('role', 'organizer')->orderBy('name')->get();

        return view('admin.movies.edit', compact('movie', 'organizers'));
    }

    
    public function update(Request $request, Movie $movie)
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
            'organizer_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['organizer_id'])) {
            $target = User::find($data['organizer_id']);
            if (!$target || $target->role !== 'organizer') {
                return back()
                    ->withErrors(['organizer_id' => 'Selected user is not an organizer.'])
                    ->withInput();
            }
        }

        
        if ((int) $data['total_seats'] !== (int) $movie->total_seats) {
            $booked = $movie->total_seats - $movie->available_seats;
            $data['available_seats'] = max(0, (int) $data['total_seats'] - $booked);
        }

        $movie->update($data);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    
    public function destroy(Movie $movie)
    {
        if ($movie->bookings()->exists()) {
            return back()->with('error',
                'Cannot delete a movie that has bookings. Deactivate it instead.');
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie deleted successfully.');
    }

    
    public function toggleStatus(Movie $movie)
    {
        $movie->update([
            'status' => $movie->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Movie status updated.');
    }

   
    public function reassign(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'organizer_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($data['organizer_id'])) {
            $target = User::find($data['organizer_id']);
            if (!$target || $target->role !== 'organizer') {
                return back()->with('error', 'Selected user is not an organizer.');
            }
        }

        $movie->update(['organizer_id' => $data['organizer_id'] ?? null]);

        return back()->with('success', 'Movie ownership updated.');
    }
}