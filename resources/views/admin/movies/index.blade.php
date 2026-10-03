@extends('layouts.app')

@section('title', 'Manage Movies')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Movies</h2>
        <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">+ Add Movie</a>
    </div>

    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Poster</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Duration</th>
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
                            <img src="{{ $movie->poster }}" alt="" style="width:50px;height:70px;object-fit:cover;">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $movie->title }}</td>
                    <td>{{ $movie->genre }}</td>
                    <td>{{ $movie->duration }} min</td>
                    <td>₹{{ number_format($movie->price, 2) }}</td>
                    <td>{{ $movie->available_seats }} / {{ $movie->total_seats }}</td>
                    <td>
                        <span class="badge {{ $movie->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $movie->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.movies.toggle', $movie) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-info">
                                {{ $movie->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this movie?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No movies yet. Click "Add Movie".</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $movies->links() }}
@endsection