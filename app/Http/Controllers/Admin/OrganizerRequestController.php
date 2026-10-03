<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizerRequestController extends Controller
{
   
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $requests = OrganizerRequest::with('user')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        $counts = [
            'pending'  => OrganizerRequest::where('status', 'pending')->count(),
            'accepted' => OrganizerRequest::where('status', 'accepted')->count(),
            'rejected' => OrganizerRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.organizer-requests.index', compact('requests', 'counts', 'status'));
    }

   
    public function accept(OrganizerRequest $organizerRequest)
    {
        if (!$organizerRequest->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        DB::transaction(function () use ($organizerRequest) {
            $organizerRequest->update([
                'status'      => 'accepted',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            $organizerRequest->user->update([
                'role' => 'organizer',
            ]);
        });

        return back()->with('success', 'Request accepted. ' . $organizerRequest->user->name . ' is now an organizer.');
    }

    public function ignore(OrganizerRequest $organizerRequest)
    {
        if (!$organizerRequest->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $organizerRequest->update([
            'status'      => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Request ignored. User remains a customer.');
    }
}