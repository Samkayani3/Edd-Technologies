<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,technician,customer',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
            ]);

            Auth::login($user);

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'technician':
                    return redirect()->route('technician.dashboard');
                case 'customer':
                    return redirect()->route('customers.dashboard');
                default:
                    return redirect()->route('login')->with('error', 'Invalid role.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                switch ($user->role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'technician':
                        return redirect()->route('technician.dashboard');
                    case 'customer':
                        return redirect()->route('customers.dashboard');
                    default:
                        return redirect()->route('login')->with('error', 'Invalid role.');
                }
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Login failed: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Logged out successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Logout failed: ' . $e->getMessage());
        }
    }
}
