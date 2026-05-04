<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function create()
    {
        // Mugamit na kita og Blade view para sa registration form
        return view('auth.register-organization');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:organizations,slug',
            'description' => 'nullable|string',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_photo')) {
            $coverPath = $request->file('cover_photo')->store('covers', 'public');
        }

        $proofPath = null;
        if ($request->hasFile('proof_of_payment')) {
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');
        }

        $org = Organization::create([
            'user_id' => auth()->id(), // Mao ni ang mag-isolate sa data
            'name' => $request->name,
            'slug' => $request->slug,
            'cover_photo' => $coverPath,
            'status' => 'pending_approval',
            'subscription_paid_at' => now(),
            'proof_of_payment' => $proofPath,
        ]);

        // Automatically create the subdomain record
        if (!$org->domains()->where('domain', $org->slug)->exists()) {
            $org->domains()->create(['domain' => $org->slug]);
        }

        // I-attach ang user sa bag-ong organization ug himuong 'org_admin' (unless already super_admin)
        $roleToSet = auth()->user()->role === 'super_admin' ? 'super_admin' : 'org_admin';
        auth()->user()->update([
            'organization_id' => $org->id,
            'role' => $roleToSet
        ]);

        return redirect()->route('register.org.payment', $org->slug)
            ->with('success', 'Registration complete! Payment received. Waiting for super admin approval.');
    }

    public function payment($org_slug)
    {
        $org = Organization::where('slug', $org_slug)->firstOrFail();

        if (auth()->id() !== $org->user_id && auth()->user()->organization_id !== $org->id) {
            abort(403);
        }

        if ($org->status === 'active') {
            $domain = config('tenancy.central_domains')[1] ?? 'localhost';
            $port = request()->getPort();
            $portString = $port == 80 || $port == 443 ? '' : ':' . $port;
            return redirect(request()->getScheme() . "://{$org->slug}.{$domain}{$portString}/dashboard");
        }

        $statusMessage = null;

        if ($org->status === 'pending_approval') {
            $statusMessage = 'Payment received. Waiting for super admin approval before your dashboard becomes available.';
        } elseif ($org->status === 'pending_payment') {
            $statusMessage = 'Please complete your ₱999 subscription payment to move your organization forward for approval.';
        } elseif ($org->status === 'pending_approval') {
            $statusMessage = 'Payment received. Waiting for super admin approval before your dashboard becomes available.';
        } elseif ($org->status === 'deactive') {
            $statusMessage = 'This organization has been disabled by the super admin. Upload proof of payment to request reactivation.';
        } elseif ($org->status === 'expired') {
            $statusMessage = 'Your subscription has expired. Upload proof of payment to renew and request reactivation.';
        }

        return view('tenant.subscription-payment', compact('org', 'statusMessage'));
    }

    public function processPayment(Request $request, $org_slug)
    {
        $org = Organization::where('slug', $org_slug)->firstOrFail();

        if (auth()->id() !== $org->user_id && auth()->user()->organization_id !== $org->id) {
            abort(403);
        }

        if ($org->status === 'active') {
            $domain = config('tenancy.central_domains')[1] ?? 'localhost';
            $port = request()->getPort();
            $portString = $port == 80 || $port == 443 ? '' : ':' . $port;
            return redirect(request()->getScheme() . "://{$org->slug}.{$domain}{$portString}/dashboard");
        }

        if ($org->status === 'pending_approval') {
            return redirect()->route('org.subscription.payment', $org->slug)
                ->with('info', 'Payment has already been submitted. Waiting for super admin approval.');
        }

        if (!in_array($org->status, ['pending_payment', 'deactive', 'expired'])) {
            return redirect()->route('org.subscription.payment', $org->slug)
                ->with('error', 'This organization is not eligible for payment at the moment.');
        }

        $request->validate([
            'payment_method' => 'required|string',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_of_payment')) {
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');
        }

        $org->update([
            'status' => 'pending_approval',
            'subscription_paid_at' => now(),
            'proof_of_payment' => $proofPath,
        ]);

        return redirect()->route('org.subscription.payment', $org->slug)
            ->with('success', 'Payment received. Your organization is now waiting for super admin approval.');
    }

    public function edit()
    {
        $org = tenant();
        
        if ($org->user_id !== auth()->id()) {
            abort(403);
        }

        $org_slug = $org->slug;
        return view('tenant.settings', compact('org', 'org_slug'));
    }

    public function update(Request $request)
    {
        $org = tenant();

        if ($org->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('covers', 'public');
        }

        $org->update($data);

        return redirect()->route('org.admin.dashboard')->with('success', 'Settings updated successfully!');
    }

    public function dashboard()
    {
        $org = tenant();

        \Illuminate\Support\Facades\Log::info('TENANT_DASHBOARD', [
            'org'        => $org ? $org->slug : 'null',
            'auth_check' => auth()->check(),
            'auth_id'    => auth()->id(),
            'session_id' => session()->getId(),
        ]);

        // In multi-database tenancy, the rooms table belongs entirely to the tenant,
        // so we can query it directly rather than through the central Organization model.
        $rooms = \App\Models\Room::with(['tutor', 'students'])->get();

        $isOwner = $org->user_id === auth()->id();

        // If user is a student, show the student dashboard directly instead of redirecting
        if (auth()->user()->isStudent()) {
            $purchases = \App\Models\FilePurchase::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->with(['file.room'])
                ->get();
            return view('student.dashboard', compact('purchases', 'org'));
        }

        // If user is not the org owner and not an org_admin, redirect them to rooms
        if (!$isOwner && auth()->user()->role !== 'org_admin') {
            return redirect('/rooms');
        }

        $totalRooms = $rooms->count();
        $totalActiveRooms = $rooms->where('status', 'open')->count();
        $totalTutors = $rooms->pluck('tutor_id')->filter()->unique()->count();
        $totalStudents = $rooms->flatMap->students->pluck('id')->filter()->unique()->count();

        // Revenue Tracking for this Tenant (Income from file sales within this organization)
        // No need to filter by organization_id because this database only contains this tenant's data.
        $totalIncome = \App\Models\FilePurchase::where('status', 'completed')
            ->join('files', 'file_purchases.file_id', '=', 'files.id')
            ->sum('files.price');

        $org_slug = $org->slug;
        return view('tenant.dashboard', compact('org', 'org_slug', 'rooms', 'totalRooms', 'totalActiveRooms', 'totalTutors', 'totalStudents', 'totalIncome'));
    }

    public function magicLogin($tenant, $token = null)
    {
        // If the token is passed as the first argument (e.g. when tenant parameter is not implicitly passed),
        // we can adjust accordingly. But usually it will be in the second argument.
        if (is_null($token)) {
            $token = $tenant;
        }
        $userId = \Illuminate\Support\Facades\Cache::pull('magic_login_' . $token);

        \Illuminate\Support\Facades\Log::info('MAGIC_LOGIN', [
            'token'      => substr($token, 0, 8),
            'userId'     => $userId,
            'session_id' => session()->getId(),
        ]);

        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                \Illuminate\Support\Facades\Auth::login($user);
                session()->save(); // force session save immediately
                \Illuminate\Support\Facades\Log::info('MAGIC_LOGIN_AFTER', [
                    'auth_check'  => auth()->check(),
                    'auth_id'     => auth()->id(),
                    'session_id'  => session()->getId(),
                ]);
                $redirectUrl = request()->query('redirect', '/dashboard');
                // Ensure the redirect is to an internal path to prevent open redirects
                if (!str_starts_with($redirectUrl, '/')) {
                    $redirectUrl = '/' . ltrim($redirectUrl, '/');
                }
                return redirect($redirectUrl);
            }
        }

        $centralDomain = config('tenancy.central_domains')[1] ?? 'localhost';
        $port = request()->getPort();
        $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
        return redirect(request()->getScheme() . '://' . $centralDomain . $portStr . '/login')
            ->with('error', 'Login session expired. Please log in again.');
    }

    public function team()
    {
        $org = tenant();
        $org->load(['rooms.tutor', 'users']);

        if ($org->user_id !== auth()->id()) {
            abort(403);
        }

        $org_slug = $org->slug;
        return view('tenant.team', compact('org', 'org_slug'));
    }

    public function invite(Request $request)
    {
        $org = tenant();

        if ($org->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|in:tutor,student',
        ]);

        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser && $existingUser->organization_id && $existingUser->organization_id !== $org->id) {
            return back()->with('error', 'This user is already assigned to another organization.');
        }

        User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'role' => $request->role,
                'organization_id' => $org->id,
                'password' => bcrypt('ChangeMe123!'),
            ]
        );

        return back()->with('success', 'Team member invited successfully.');
    }
}
