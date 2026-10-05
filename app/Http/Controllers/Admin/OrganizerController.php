<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OrganizerController extends Controller
{
    
    public function index(Request $request)
    {
        $query = User::where('role', 'organizer')
            ->withCount('movies')
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $organizers = $query->paginate(15)->withQueryString();

        return view('admin.organizers.index', compact('organizers'));
    }

   
    public function create()
    {
        return view('admin.organizers.create');
    }

   
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'organizer',
        ]);

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Organizer created successfully.');
    }

   
    public function edit(User $organizer)
    {
        if ($organizer->role !== 'organizer') {
            abort(404, 'User is not an organizer.');
        }

        return view('admin.organizers.edit', compact('organizer'));
    }


    public function update(Request $request, User $organizer)
    {
        if ($organizer->role !== 'organizer') {
            abort(404);
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($organizer->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $organizer->name  = $data['name'];
        $organizer->email = $data['email'];

        if (!empty($data['password'])) {
            $organizer->password = Hash::make($data['password']);
        }

        $organizer->save();

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Organizer updated successfully.');
    }

   
    public function demote(User $organizer)
    {
        if ($organizer->role !== 'organizer') {
            abort(404);
        }

        if ($organizer->movies()->exists()) {
            return back()->with('error',
                'This organizer still owns movies. Reassign or delete them first.');
        }

        $organizer->update(['role' => 'customer']);

        return redirect()->route('admin.organizers.index')
            ->with('success', $organizer->name . ' was demoted to customer.');
    }


    public function promoteForm()
    {
        $customers = User::where('role', 'customer')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.organizers.promote', compact('customers'));
    }


    public function promote(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($data['user_id']);

        if ($user->role === 'organizer') {
            return back()->with('error', 'User is already an organizer.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Admins cannot be promoted to organizer.');
        }

        $user->update(['role' => 'organizer']);

        return redirect()->route('admin.organizers.index')
            ->with('success', $user->name . ' is now an organizer.');
    }


    public function destroy(User $organizer)
    {
        if ($organizer->role !== 'organizer') {
            abort(404);
        }

        if ($organizer->movies()->exists()) {
            return back()->with('error',
                'Cannot delete an organizer that still owns movies. Reassign them first.');
        }

        if ($organizer->bookings()->exists()) {
            return back()->with('error',
                'Cannot delete an organizer who has customer bookings. Demote instead.');
        }

        $organizer->delete();

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Organizer deleted.');
    }


    public function movies(User $organizer)
    {
        if ($organizer->role !== 'organizer') {
            abort(404);
        }

        $movies = Movie::where('organizer_id', $organizer->id)
            ->latest()
            ->paginate(15);

        $organizers = User::where('role', 'organizer')
            ->where('id', '!=', $organizer->id)
            ->orderBy('name')
            ->get();

        return view('admin.organizers.movies', compact('organizer', 'movies', 'organizers'));
    }
}