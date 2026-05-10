<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\FilePurchase;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $totalOrgs = Organization::count();
            $totalUsers = User::count();
            $totalTeachers = User::whereIn('role', ['teacher', 'tutor'])->count();
            $totalStudents = User::where('role', 'student')->count();
            $totalActiveRooms = 0;
            $activeOrgs = Organization::where('status', 'active')->get();
            foreach ($activeOrgs as $org) {
                try {
                    tenancy()->initialize($org);
                    $totalActiveRooms += Room::where('status', '!=', 'archived')->count();
                    tenancy()->end();
                } catch (\Exception $e) {
                    if (tenancy()->initialized) tenancy()->end();
                }
            }
            $totalPaymentsMade = Organization::sum('total_payments_made');
            $totalIncome = $totalPaymentsMade * 999; // Cumulative tenant subscription revenue
            
            $recentOrganizations = Organization::with('owner')->latest()->take(5)->get();
            $pendingOrganizations = Organization::where('status', 'pending_approval')->with('owner')->get();
            
            return view('admin.dashboard', compact('totalOrgs', 'totalUsers', 'totalTeachers', 'totalStudents', 'totalActiveRooms', 'totalIncome', 'recentOrganizations', 'pendingOrganizations'));
        }

        // Redirect Admins and Org Admins to their organization's subdomain dashboard.
        // Students and Teachers remain on the central domain for their unified dashboard.
        if (!$user->isStudent() && !$user->isTeacher() && ($user->isAdmin() || $user->role === 'org_admin' || $user->organization_id)) {
            // First try: find by owner (user_id)
            $org = Organization::where('user_id', $user->id)->first();

            // Second try: find by organization_id (foreign key on users table — integer id)
            if (!$org && $user->organization_id) {
                $org = Organization::where('id', $user->organization_id)->first();
            }

            if (!$org && ($user->isAdmin() || $user->role === 'org_admin')) {
                return redirect()->route('register.org');
            } elseif (!$org) {
                \Illuminate\Support\Facades\Log::info('DASH_DEBUG: Org is null for user', ['id' => $user->id]);
                // If a normal user doesn't have an org, they just fall through to the central views
                $org = null;
            }

            if ($org) {
                \Illuminate\Support\Facades\Log::info('DASH_DEBUG: Redirecting user to magic login', ['user' => $user->id, 'org' => $org->slug]);

            $org_slug = $org->slug;

            if ($org->status !== 'active') {
                return redirect()->route('org.subscription.payment', $org->slug)
                    ->with('info', $org->status === 'pending_payment'
                        ? 'Please complete your ₱999 payment to activate your organization.'
                        : ($org->status === 'pending_approval'
                            ? 'Payment has been received. Waiting for super admin approval.'
                            : 'Your organization is not active yet.'));
            }

            // Determine central domain and port
            $centralDomains = config('tenancy.central_domains', ['localhost']);
            $centralDomain  = $centralDomains[0];
            $port           = request()->getPort();
            $portStr        = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
            $scheme         = request()->getScheme();

            // Generate magic token and redirect to tenant subdomain
            $token = \Illuminate\Support\Str::random(40);
            \Illuminate\Support\Facades\Cache::put('magic_login_' . $token, $user->id, now()->addSeconds(120));
            return redirect($scheme . '://' . $org->slug . '.' . $centralDomain . $portStr . '/magic-login/' . $token);
            }
        }

        if ($user->isTeacher()) {
            $rooms = $user->allTaughtRooms();
            $pendingRequests = $user->allPendingRequests();
            $pendingJoinRequests = $user->allPendingJoinRequests();

            $pendingCount = $pendingRequests->count();
            $pendingJoinCount = $pendingJoinRequests->count();
            return view('teacher.dashboard', compact('rooms', 'pendingRequests', 'pendingCount', 'pendingJoinRequests', 'pendingJoinCount'));
        }

        if ($user->isStudent()) {
            // Purchases live in each tenant's DB — use the cross-DB helper
            $purchases = $user->allPurchasedFiles();

            return view('student.dashboard', compact('purchases'));
        }

        return redirect('/');
    }
}
