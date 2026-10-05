@extends('layouts.app')

@section('title', 'Create Organizer')

@section('content')
    <h2>Create Organizer</h2>

    <form action="{{ route('admin.organizers.store') }}" method="POST"
          class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control" required>
            <small class="text-muted">Minimum 6 characters.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-primary">Create Organizer</button>
        <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection