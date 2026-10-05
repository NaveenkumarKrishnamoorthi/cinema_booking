@extends('layouts.app')

@section('title', 'Edit Organizer')

@section('content')
    <h2>Edit Organizer</h2>

    <form action="{{ route('admin.organizers.update', $organizer) }}" method="POST"
          class="bg-white p-4 rounded shadow-sm">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $organizer->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $organizer->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control">
            <small class="text-muted">Leave blank to keep current password.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-primary">Update Organizer</button>
        <a href="{{ route('admin.organizers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection