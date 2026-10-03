@extends('layouts.app')

@section('title', 'Organizer Requests')

@section('content')
    <h2>Organizer Requests</h2>

    <ul class="nav nav-pills my-3">
        <li class="nav-item">
            <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}"
               href="{{ route('admin.organizer-requests.index', ['status' => 'pending']) }}">
                Pending ({{ $counts['pending'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'accepted' ? 'active' : '' }}"
               href="{{ route('admin.organizer-requests.index', ['status' => 'accepted']) }}">
                Accepted ({{ $counts['accepted'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}"
               href="{{ route('admin.organizer-requests.index', ['status' => 'rejected']) }}">
                Rejected ({{ $counts['rejected'] }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}"
               href="{{ route('admin.organizer-requests.index', ['status' => 'all']) }}">
                All
            </a>
        </li>
    </ul>

    <table class="table table-bordered bg-white align-middle">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Reason</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
                <tr>
                    <td>
                        {{ $req->user->name }}<br>
                        <small class="text-muted">{{ $req->user->email }}</small>
                    </td>
                    <td style="max-width:400px;">{{ $req->reason }}</td>
                    <td>{{ $req->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge bg-{{
                            $req->status === 'pending' ? 'warning' :
                            ($req->status === 'accepted' ? 'success' : 'danger')
                        }}">{{ ucfirst($req->status) }}</span>
                    </td>
                    <td>
                        @if($req->status === 'pending')
                            <form action="{{ route('admin.organizer-requests.accept', $req) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">Accept</button>
                            </form>
                            <form action="{{ route('admin.organizer-requests.ignore', $req) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Ignore this request?');">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger">Ignore</button>
                            </form>
                        @else
                            <small class="text-muted">
                                Reviewed {{ $req->reviewed_at?->diffForHumans() }}
                            </small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4">No requests found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $requests->links() }}
@endsection