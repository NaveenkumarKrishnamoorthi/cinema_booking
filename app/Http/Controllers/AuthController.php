<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show index landing page
    public function index()
    {
        if (Auth::check()) {
             return match (Auth::user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'organizer' => redirect()->route('organizer.dashboard'),
            default     => redirect()->route('customer.dashboard'),
        };
        }
        return view('index');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

           return match (Auth::user()->role) {
        'admin'     => redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!'),
        'organizer' => redirect()->route('organizer.dashboard')->with('success', 'Welcome Organizer!'),
        default     => redirect()->route('customer.dashboard')->with('success', 'Welcome back!'),
    };
}

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->onlyInput('email');
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration (only customers)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Registration successful!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Logged out successfully.');
    }
}