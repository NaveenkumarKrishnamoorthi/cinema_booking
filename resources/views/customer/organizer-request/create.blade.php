@extends('layouts.app')

@section('title', 'Request Organizer')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"> Request to Become an Organizer</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Organizers can create and manage their own movies/events, track bookings,
                        and view revenue. Tell us why you want to become an organizer.
                    </p>

                    <form method="POST" action="{{ route('customer.organizer-request.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Reason *</label>
                            <textarea name="reason" rows="5"
                                      class="form-control @error('reason') is-invalid @enderror"
                                      minlength="10" maxlength="1000" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">10 – 1000 characters.</small>
                        </div>

                        <button class="btn btn-warning">Submit Request</button>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection