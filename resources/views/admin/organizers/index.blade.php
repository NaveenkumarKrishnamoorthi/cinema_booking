@extends('layouts.app')

@section('title', 'Manage Organizers')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2> Organizers</h2>
        <div>
            
            <a href="{{ route('admin.organizers.create') }}" class="btn btn-primary">
                + New Organizer
            </a>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Search name or email..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <table class="table table-bordered bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Movies</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($organizers as $org)
                <tr>
                    <td>{{ $org->name }}</td>
                    <td>{{ $org->email }}</td>
                    <td>
                        <span class="badge bg-info">{{ $org->movies_count }}</span>
                        @if($org->movies_count > 0)
                            <a href="{{ route('admin.organizers.movies', $org) }}"
                               class="btn btn-sm btn-outline-primary ms-1">View</a>
                        @endif
                    </td>
                    <td>{{ $org->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.organizers.edit', $org) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.organizers.demote', $org) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Demote {{ $org->name }} to customer?');">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-secondary">Demote</button>
                        </form>

                        <form action="{{ route('admin.organizers.destroy', $org) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete {{ $org->name }}?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        No organizers yet.
                        <a href="{{ route('admin.organizers.create') }}">Create one →</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $organizers->links() }}
@endsection