<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * I-redirect ang user sa Google Login page.
     */
    public function redirectToGoogle(\Illuminate\Http\Request $request)
    {
        if ($request->has('action')) {
            session(['google_auth_action' => $request->action]);
        }
        if ($request->has('role')) {
            session(['google_auth_role' => $request->role]);
        }
        if ($request->has('org_name')) {
            session(['google_auth_org_name' => $request->org_name]);
        }

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Dawaton ang response gikan sa Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }

        $action = session('google_auth_action', 'login');
        $role = session('google_auth_role', 'student');
        $orgName = session('google_auth_org_name');
        
        session()->forget(['google_auth_action', 'google_auth_role', 'google_auth_org_name']);

        // Pangitaon ang user base sa google_id o email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($action === 'register') {
            if ($user) {
                // Kon naa na ang user, i-update lang ang google_id kon wala pa
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                Auth::login($user);
                return redirect()->to('/');
            }

            // Valid roles only
            if (!in_array($role, ['student', 'teacher', 'admin'])) {
                $role = 'student';
            }

            // Kon wala pa, i-create ang bag-ong user (Registration)
            $user = User::create([
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt('123456dummy'),
                'role' => ($role === 'admin' ? 'super_admin' : $role), // Temporary set to super_admin during org creation, or maybe wait. Actually in AuthController, admin is set to 'admin' initially until approved.
            ]);
            
            // Replicate AuthController logic for admin
            if ($role === 'admin') {
                $user->update(['role' => 'student']); // Will be upgraded upon approval

                if ($orgName) {
                    $slug = \Illuminate\Support\Str::slug($orgName);
                    // Ensure slug uniqueness
                    $originalSlug = $slug;
                    $counter = 1;
                    while (\App\Models\Organization::where('slug', $slug)->exists()) {
                        $slug = $originalSlug . '-' . $counter++;
                    }

                    \App\Models\Organization::create([
                        'name' => $orgName,
                        'slug' => $slug,
                        'user_id' => $user->id,
                        'status' => 'pending_approval',
                    ]);
                }
            }

            Auth::login($user);
            return redirect()->to('/');
        } else {
            // Login mode: Kinahanglan na-register na
            if (!$user) {
                return redirect('/login')->with('error', 'This account is not registered. Please register first.');
            }

            // I-update ang google_id kon email ra ang na-match sa una
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            Auth::login($user);
            return redirect()->to('/');
        }
    }
}
