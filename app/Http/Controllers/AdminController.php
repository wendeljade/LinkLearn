<?php

namespace App\Http\Controllers;

use App\Models\Organization;
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

    public function dashboard()
    {
        $totalOrgs = \App\Models\Organization::count();
        $totalUsers = \App\Models\User::count();
        $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $totalActiveRooms = \App\Models\Room::where('status', 'open')->count();

        // Revenue Tracking: Total monthly tenant subscription income from organizations that have paid.
        $paidOrganizations = \App\Models\Organization::where('status', 'active')->count();
        $totalIncome = $paidOrganizations * 999;

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
        } elseif ($org->status === 'deactive') {
            $org->status = 'active';
        } else {
            return back()->with('error', 'Only active or disabled organizations can be toggled here.');
        }

        $org->save();

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

        // Mark org as active AFTER provisioning succeeds
        $org->update(['status' => 'active']);

        return back()->with('success', "Organization approved! Tenant database provisioned. They can now access: {$org->slug}.localhost:8000");
    }

    public function monitoring()
    {
        $rawTeachers = User::where('role', 'teacher')->with('organization')->latest()->get();
        $rawStudents = User::where('role', 'student')->with('organization')->latest()->get();

        $teachers = collect();
        foreach ($rawTeachers as $teacher) {
            $taughtRooms = $teacher->allTaughtRooms();
            $orgsTeachingIn = $taughtRooms->pluck('organization')->unique('id');

            if ($orgsTeachingIn->isEmpty()) {
                $teachers->push($teacher);
            } else {
                foreach ($orgsTeachingIn as $org) {
                    $clonedTeacher = clone $teacher;
                    $clonedTeacher->setRelation('organization', $org);
                    $teachers->push($clonedTeacher);
                }
            }
        }

        $students = collect();
        foreach ($rawStudents as $student) {
            $joinedRooms = $student->allJoinedRooms();
            $orgsStudyingIn = $joinedRooms->pluck('organization')->unique('id');

            if ($orgsStudyingIn->isEmpty()) {
                $students->push($student);
            } else {
                foreach ($orgsStudyingIn as $org) {
                    $clonedStudent = clone $student;
                    $clonedStudent->setRelation('organization', $org);
                    $students->push($clonedStudent);
                }
            }
        }

        return view('admin.monitoring', compact('teachers', 'students'));
    }
}
