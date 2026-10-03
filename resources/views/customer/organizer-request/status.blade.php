@extends('layouts.app')

@section('title', 'Organizer Request Status')

@section('content')
    <h2>Your Organizer Requests</h2>

    @forelse($requests as $req)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>Submitted:</strong> {{ $req->created_at->format('M d, Y H:i') }}
                    </div>
                    <div>
                        @if($req->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($req->status === 'accepted')
                            <span class="badge bg-success">Accepted</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>
                </div>
                <p class="mt-2 mb-0"><em>{{ $req->reason }}</em></p>
                @if($req->reviewed_at)
                    <small class="text-muted">
                        Reviewed on {{ $req->reviewed_at->format('M d, Y H:i') }}
                    </small>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            You haven't submitted any organizer requests.
            <a href="{{ route('customer.organizer-request.create') }}">Submit one →</a>
        </div>
    @endforelse

    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">← Back to Dashboard</a>
@endsection