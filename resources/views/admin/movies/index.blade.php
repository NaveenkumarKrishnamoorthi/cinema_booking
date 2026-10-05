@extends('layouts.app')

@section('title', 'Manage Movies')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2> All Movies</h2>
        <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">+ Add Movie</a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search title..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="owner" class="form-select">
                <option value="">All owners</option>
                <option value="admin"     {{ request('owner') === 'admin'     ? 'selected' : '' }}>Admin-owned only</option>
                <option value="organizer" {{ request('owner') === 'organizer' ? 'selected' : '' }}>Organizer-owned only</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="organizer_id" class="form-select">
                <option value="">Any organizer</option>
                @foreach($organizers as $org)
                    <option value="{{ $org->id }}"
                        {{ (string) request('organizer_id') === (string) $org->id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th>Poster</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Owner</th>
                <th>Price</th>
                <th>Seats</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
                <tr>
                    <td>
                        @if($movie->poster)
                            <img src="{{ $movie->poster }}"
                                 style="width:50px;height:70px;object-fit:cover;">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $movie->title }}</td>
                    <td>{{ $movie->genre }}</td>
                    <td>
                        @if($movie->organizer)
                            <span class="badge bg-warning text-dark">
                                 {{ $movie->organizer->name }}
                            </span>
                        @else
                            <span class="badge bg-primary">Admin</span>
                        @endif
                    </td>
                    <td>${{ number_format($movie->price, 2) }}</td>
                    <td>{{ $movie->available_seats }} / {{ $movie->total_seats }}</td>
                    <td>
                        <span class="badge {{ $movie->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $movie->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.movies.edit', $movie) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.movies.toggle', $movie) }}"
                              method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-info">
                                {{ $movie->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.movies.destroy', $movie) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this movie?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        No movies found.
                        <a href="{{ route('admin.movies.create') }}">Create one →</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $movies->links() }}
@endsection