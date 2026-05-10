<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function viewProof($filename)
    {
        $path = storage_path('app/public/proofs/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function supportTickets(Request $request)
    {
        $query = SupportTicket::with(['user', 'organization'])->latest();

        if ($request->has('status') && in_array($request->status, ['open', 'closed'])) {
            $query->where('status', $request->status);
        }

        $tickets = $query->get();
        return view('admin.support.index', compact('tickets'));
    }

    public function viewSupportTicket($id)
    {
        $ticket = SupportTicket::with(['user', 'organization', 'messages.user'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function replySupportTicket(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        if ($request->has('close_ticket') && $request->close_ticket == '1') {
            $ticket->update(['status' => 'closed']);

            \App\Models\Notification::create([
                'user_id' => $ticket->user_id,
                'type' => 'support_ticket_closed',
                'title' => 'Support Ticket Closed',
                'message' => 'Your support ticket "' . $ticket->subject . '" has been closed by the admin.',
                'link' => route('support.show', $ticket->id),
                'icon' => '🔒'
            ]);

            return back()->with('success', 'Ticket closed successfully.');
        }

        if ($ticket->status === 'closed') {
            return back()->with('error', 'This ticket is closed.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        \App\Models\Notification::create([
            'user_id' => $ticket->user_id,
            'type' => 'support_ticket_admin_reply',
            'title' => 'Admin Replied to Ticket',
            'message' => 'An admin has replied to your support ticket: "' . $ticket->subject . '".',
            'link' => route('support.show', $ticket->id),
            'icon' => '👨‍💻'
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function acknowledgeUpdate(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Only Super Admin and Tenant Admin can acknowledge updates
        if (!$user->isSuperAdmin() && $user->role !== 'org_admin') {
            abort(403, 'Unauthorized action. Only administrators can perform updates.');
        }

        $request->validate(['version' => 'required|string']);
        
        $output = '';

        if (tenant()) {
            // Tenant Domain Update (Org Admin)
            try {
                \Illuminate\Support\Facades\Artisan::call('tenants:migrate', ['--tenants' => [tenant('id')], '--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Tenant Update Failed: " . $e->getMessage());
            }

            // Set cache AFTER optimize:clear so it isn't immediately wiped
            \Illuminate\Support\Facades\Cache::forever('tenant_version_' . tenant('id'), $request->version);

        } else {
            // Central Domain Update (Super Admin)
            if (!$user->isSuperAdmin()) {
                abort(403);
            }

            try {
                // Actually update the global code and run central migrations
                $basePath = base_path();
                $scriptPath = base_path('update.bat');
                shell_exec('cd "' . $basePath . '" && cmd.exe /c "' . $scriptPath . '" 2>&1');
                
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("System Update Failed: " . $e->getMessage());
            }

            // Set cache AFTER optimize:clear so it isn't immediately wiped
            \Illuminate\Support\Facades\Cache::forever('central_version', $request->version);
        }
        
        return back()->with('success', 'System successfully updated to ' . $request->version);
    }

    public function dashboard()
    {
        $totalOrgs = \App\Models\Organization::count();
        $totalUsers = \App\Models\User::count();
        $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $totalActiveRooms = 0;
        $activeOrgs = \App\Models\Organization::where('status', 'active')->get();
        foreach ($activeOrgs as $org) {
            try {
                tenancy()->initialize($org);
                $totalActiveRooms += \App\Models\Room::where('status', '!=', 'archived')->count();
                tenancy()->end();
            } catch (\Exception $e) {
                if (tenancy()->initialized) tenancy()->end();
            }
        }

        // Revenue Tracking: Total monthly tenant subscription income from organizations that have paid.
        $totalPaymentsMade = \App\Models\Organization::sum('total_payments_made');
        $totalIncome = $totalPaymentsMade * 999;

        $recentOrganizations = \App\Models\Organization::with('owner')->latest()->take(5)->get();
        $pendingOrganizations = \App\Models\Organization::where('status', 'pending_approval')->with('owner')->get();

        return view('admin.dashboard', compact('totalOrgs', 'totalUsers', 'totalTeachers', 'totalStudents', 'totalActiveRooms', 'totalIncome', 'recentOrganizations', 'pendingOrganizations'));
    }

    public function organizations()
    {
        $organizations = Organization::with('owner')->latest()->get();
        return view('admin.organizations', compact('organizations'));
    }

    public function toggleStatus($id)
    {
        $org = Organization::where('slug', $id)->firstOrFail();

        if ($org->status === 'active') {
            $org->status = 'deactive';
            $notificationTitle = 'Organization Disabled';
            $notificationMessage = 'Your organization "' . $org->name . '" has been disabled by the system administrator.';
            $notificationIcon = '⚠️';
        } elseif ($org->status === 'deactive') {
            $org->status = 'active';
            $notificationTitle = 'Organization Re-activated';
            $notificationMessage = 'Your organization "' . $org->name . '" has been re-activated by the system administrator.';
            $notificationIcon = '✅';
        } else {
            return back()->with('error', 'Only active or disabled organizations can be toggled here.');
        }

        $org->save();

        if ($org->user_id) {
            \App\Models\Notification::create([
                'user_id' => $org->user_id,
                'type' => 'org_status_changed',
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'icon' => $notificationIcon
            ]);
        }

        return back()->with('success', 'Organization status updated successfully.');
    }

    public function approve($id)
    {
        $org = Organization::where('slug', $id)->firstOrFail();

        if ($org->status !== 'pending_approval') {
            return back()->with('error', 'Only organizations with completed payment can be approved.');
        }

        // Promote the owner to org_admin role if they aren't already
        if ($org->owner) {
            $roleToSet = $org->owner->role === 'super_admin' ? 'super_admin' : 'org_admin';
            $org->owner->update(['role' => $roleToSet, 'organization_id' => $org->id]);
        }

        // Create the subdomain record so Subdomain Tenancy can route it (avoid duplicates)
        if (!$org->domains()->where('domain', $org->slug)->exists()) {
            $org->domains()->create(['domain' => $org->slug]);
        }

        // Check if the tenant database already exists to prevent double-provisioning
        $dbName = 'linklearn_org_' . $org->slug;
        $dbExists = DB::connection('central')
            ->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

        if (empty($dbExists)) {
            // Database does not exist — provision it via the TenantCreated event pipeline
            try {
                event(new \Stancl\Tenancy\Events\TenantCreated($org));
                Log::info("Tenant DB provisioned: {$dbName}");
            } catch (\Exception $e) {
                Log::error("Tenant DB provisioning failed for {$org->slug}: " . $e->getMessage());
                return back()->with('error', 'Approval saved but database provisioning failed: ' . $e->getMessage());
            }
        } else {
            // DB already exists — just run migrations to ensure they are up-to-date
            try {
                tenancy()->initialize($org);
                Artisan::call('tenants:migrate', [
                    '--tenants' => [$org->getTenantKey()],
                    '--force'   => true,
                ]);
                tenancy()->end();
                Log::info("Tenant DB already existed, ran migrations: {$dbName}");
            } catch (\Exception $e) {
                Log::warning("Could not run tenant migrations for {$org->slug}: " . $e->getMessage());
            }
        }

        // Mark org as active AFTER provisioning succeeds and record the payment
        $org->update([
            'status' => 'active',
            'total_payments_made' => $org->total_payments_made + 1
        ]);

        if ($org->user_id) {
            \App\Models\Notification::create([
                'user_id' => $org->user_id,
                'type' => 'org_approved',
                'title' => 'Organization Approved 🎉',
                'message' => 'Your organization "' . $org->name . '" has been approved! You can now start creating classrooms.',
                'link' => route('dashboard'), // They can go to dashboard to see it
                'icon' => '🏢'
            ]);
        }

        return back()->with('success', "Organization approved! Tenant database provisioned. They can now access: {$org->slug}.localhost:8000");
    }

    public function monitoring()
    {
        $rawTeachers = User::where('role', 'teacher')->with('organization')->latest()->get();
        $rawStudents = User::where('role', 'student')->with('organization')->latest()->get();

        // Build a deduplicated list: one entry per user, with all orgs attached
        $teachers = collect();
        foreach ($rawTeachers as $teacher) {
            $taughtRooms   = $teacher->allTaughtRooms();
            $orgsTeachingIn = $taughtRooms->pluck('organization')->filter()->unique('id')->values();

            // If no rooms/orgs found, fallback to their registered org (if any)
            if ($orgsTeachingIn->isEmpty() && $teacher->organization) {
                $orgsTeachingIn = collect([$teacher->organization]);
            }

            $teacher->all_organizations = $orgsTeachingIn;
            $teachers->push($teacher);
        }

        $students = collect();
        foreach ($rawStudents as $student) {
            $joinedRooms    = $student->allJoinedRooms();
            $orgsStudyingIn = $joinedRooms->pluck('organization')->filter()->unique('id')->values();

            // If no rooms/orgs found, fallback to their registered org (if any)
            if ($orgsStudyingIn->isEmpty() && $student->organization) {
                $orgsStudyingIn = collect([$student->organization]);
            }

            $student->all_organizations = $orgsStudyingIn;
            $students->push($student);
        }

        return view('admin.monitoring', compact('teachers', 'students'));
    }
}
