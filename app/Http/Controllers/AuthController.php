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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:student,teacher,admin',
            'org_name' => 'required_if:role,admin|nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'admin') {
            $slug = \Illuminate\Support\Str::slug($request->org_name);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $count = 1;
            while (\App\Models\Organization::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $org = \App\Models\Organization::create([
                'name' => $request->org_name,
                'slug' => $slug,
                'user_id' => $user->id,
                'status' => 'pending_payment',
            ]);

            // Update user with organization_id and set role to org_admin for consistency
            $user->update([
                'organization_id' => $org->id,
                'role' => 'org_admin'
            ]);

            Auth::login($user);
            return redirect()->route('register.org.payment', $slug);
        }

        Auth::login($user);
        return redirect()->to('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
