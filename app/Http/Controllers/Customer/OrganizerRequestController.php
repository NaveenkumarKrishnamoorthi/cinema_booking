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

        