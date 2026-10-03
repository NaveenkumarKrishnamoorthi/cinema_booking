<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrganizerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerRequestController extends Controller
{
    
    public function create()
    {
        $user = Auth::user();

        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard')
                ->with('info', 'You are already an organizer.');
        }

        f ($user->hasPendingOrganizerRequest()) {
            return redirect()->route('customer.organizer-request.status')
                ->with('info', 'Your organizer request is pending review.');
        }

        return view('customer.organizer-request.create');
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard');
        }

        if ($user->hasPendingOrganizerRequest()) {
            return back()->with('error', 'You already have a pending request.');
        }

        $data = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        OrganizerRequest::create([
            'user_id' => $user->id,
            'reason'  => $data['reason'],
            'status'  => 'pending',
        ]);

        return redirect()->route('customer.organizer-request.status')
            ->with('success', 'Your organizer request has been submitted. You will be notified once reviewed.');
    }

 
    public function status()
    {
        $requests = OrganizerRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.organizer-request.status', compact('requests'));
    }
}