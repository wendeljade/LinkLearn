<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\File;
use App\Models\Room;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    // Helper: resolve current org from request attribute OR tenant() (subdomain context)
    private function resolveOrg()
    {
        $org = request()->get('current_org');
        if (!$org && function_exists('tenant') && tenant()) {
            $org = tenant();
        }
        return $org;
    }

    // 1. I-display ang tanang rooms. If org slug exists, show org-scoped room list.
    public function index()
    {
        $org = $this->resolveOrg();
        $user = auth()->user();

        if ($org) {
            // In an org context, show all active rooms in the org if they have permission
            if (!$user->isAdmin() && $user->role !== 'org_admin' && $user->organization_id !== $org->id) {
                // In tenant DB context (subdomain), organization_id check is irrelevant — all users belong here
                $isTenantContext = function_exists('tenant') && tenant();
                if (!$isTenantContext) {
                    abort(403, 'Unauthorized access to this organization.');
                }
            }

            // On tenant subdomain: rooms table has no organization_id column, query directly
            $isTenantContext = function_exists('tenant') && tenant();
            if ($isTenantContext) {
                if ($user->isAdmin() || $user->role === 'org_admin') {
                    $rooms = Room::with(['tutor', 'students'])->where('status', 'open')->latest()->get();
                } elseif ($user->isTeacher()) {
                    $rooms = Room::with(['tutor', 'students'])->where('tutor_id', $user->id)->where('status', 'open')->latest()->get();
                } else {
                    $rooms = $user->allJoinedRooms();
                }
            } else {
                $rooms = $org->rooms()->with(['tutor', 'organization'])->where('status', 'open')->latest()->get();
            }
            return view('rooms.index', compact('rooms', 'org'));
        }

        // Global context (My Classrooms or All Available)
        if ($user->isAdmin() && !$user->organization_id) {
            // Only truly global Super Admins see EVERYTHING
            $rooms = Room::where('status', 'open')
                         ->with(['tutor', 'organization'])
                         ->latest()
                         ->get();
        } elseif ($user->role === 'org_admin' || $user->isAdmin()) {
            // Org Admins and localized Admins see all rooms in THEIR org
            $rooms = Room::where('organization_id', $user->organization_id)
                         ->where('status', 'open')
                         ->with(['tutor', 'organization'])
                         ->latest()
                         ->get();
        } elseif ($user->isTeacher()) {
            // Teachers see all classrooms they teach across all organizations
            $rooms = $user->allTaughtRooms();
            // is_member and is_tutor are already set by allTaughtRooms()
        } else {
            // Students see the classrooms they have joined, regardless of org assignment
            $rooms = $user->allJoinedRooms();
        }

        return view('rooms.index', compact('rooms'));
    }

    public function explore()
    {
        $user = auth()->user();
        if (!$user->isStudent()) {
            return redirect()->route('rooms.index');
        }

        $rooms = $user->allExploreRooms();
        $isExplore = true;

        return view('rooms.index', compact('rooms', 'isExplore'));
    }

    public function archived()
    {
        $org = $this->resolveOrg();
        $user = auth()->user();

        if ($org) {
            // In an org context, show archived rooms in the org if they have permission
            if (!$user->isAdmin() && $user->role !== 'org_admin' && $user->organization_id !== $org->id) {
                $isTenantContext = function_exists('tenant') && tenant();
                if (!$isTenantContext) {
                    abort(403, 'Unauthorized access to this organization.');
                }
            }

            // On tenant subdomain
            $isTenantContext = function_exists('tenant') && tenant();
            if ($isTenantContext) {
                if ($user->isAdmin() || $user->role === 'org_admin') {
                    $rooms = Room::with(['tutor', 'students'])->where('status', 'archived')->latest()->get();
                } elseif ($user->isTeacher()) {
                    $rooms = Room::with(['tutor', 'students'])->where('tutor_id', $user->id)->where('status', 'archived')->latest()->get();
                } else {
                    $rooms = $user->allJoinedRooms('archived');
                }
            } else {
                $rooms = $org->rooms()->with(['tutor', 'organization'])->where('status', 'archived')->latest()->get();
            }
            return view('rooms.archived', compact('rooms', 'org'));
        }

        // Global context (My Classrooms or All Available)
        if ($user->isAdmin() && !$user->organization_id) {
            $rooms = Room::where('status', 'archived')
                         ->with(['tutor', 'organization'])
                         ->latest()
                         ->get();
        } elseif ($user->role === 'org_admin' || $user->isAdmin()) {
            $rooms = Room::where('organization_id', $user->organization_id)
                         ->where('status', 'archived')
                         ->with(['tutor', 'organization'])
                         ->latest()
                         ->get();
        } elseif ($user->isTeacher()) {
            $rooms = $user->allTaughtRooms('archived');
        } else {
            $rooms = $user->allJoinedRooms('archived');
        }

        return view('rooms.archived', compact('rooms'));
    }

    public function archive(Room $room)
    {
        if ($room->tutor_id !== auth()->id()) abort(403);
        $room->update(['status' => 'archived']);
        return back()->with('success', 'Room archived successfully!');
    }

    public function unarchive(Room $room)
    {
        if ($room->tutor_id !== auth()->id()) abort(403);
        $room->update(['status' => 'open']);
        return back()->with('success', 'Room unarchived successfully!');
    }

    public function archiveTenant($roomId, $orgSlug)
    {
        $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
        tenancy()->initialize($org);
        
        $room = Room::findOrFail($roomId);
        if ($room->tutor_id !== auth()->id()) abort(403);
        $room->update(['status' => 'archived']);
        
        tenancy()->end();
        return back()->with('success', 'Room archived successfully!');
    }

    public function unarchiveTenant($roomId, $orgSlug)
    {
        $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
        tenancy()->initialize($org);
        
        $room = Room::findOrFail($roomId);
        if ($room->tutor_id !== auth()->id()) abort(403);
        $room->update(['status' => 'open']);
        
        tenancy()->end();
        return back()->with('success', 'Room unarchived successfully!');
    }


    // 2. I-pakita ang Form para maghimo og room (Para sa Admin sa org o Teacher)
    public function create()
    {
        $org = $this->resolveOrg();
        $user = auth()->user();
        
        // Allow SuperAdmin and OrgAdmin to create rooms only
        if (!$user->isAdmin() && $user->role !== 'org_admin') {
            abort(403, 'Only organization admins can create classrooms.');
        }

        if ($org) {
            // If in an org context, teacher must belong to that org
            // On tenant DB, all users are part of this org — skip org_id check
            $isTenantContext = function_exists('tenant') && tenant();
            if (!$isTenantContext && !$user->isAdmin() && $user->organization_id !== $org->id) {
                abort(403, 'Unauthorized access to this organization.');
            }
        }

        return view('rooms.create', compact('org'));
    }

    // 3. I-save ang bag-ong Room sa database para sa kasamtangang organisasyon
    public function store(Request $request)
    {
        $user = auth()->user();

        // Allow SuperAdmin and OrgAdmin to create rooms only
        if (!$user->isAdmin() && $user->role !== 'org_admin') {
            abort(403, 'Only organization admins can create classrooms.');
        }

        $org = $this->resolveOrg();

        $request->validate([
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:5120',
        ]);

        $cover_path = null;
        if ($request->hasFile('cover_photo')) {
            $cover_path = $request->file('cover_photo')->store('rooms/covers', 'central_public');
        }

        // Determine tutor_id: If teacher, assign themselves. If admin, leave for later invite.
        $tutor_id = ($user->isTeacher() || $user->role === 'tutor') ? $user->id : null;

        // On tenant subdomain: rooms table has NO organization_id column — omit it
        $isTenantContext = function_exists('tenant') && tenant();
        $roomData = [
            'tutor_id'     => $tutor_id,
            'subject_name' => $request->subject_name,
            'description'  => $request->description,
            'cover_photo'  => $cover_path,
            'fee'          => 0,
            'status'       => 'open',
        ];

        // Only set organization_id on the central DB (not on tenant DB)
        if (!$isTenantContext) {
            $organization_id = $org?->id ?? $user->organization_id;
            $roomData['organization_id'] = $organization_id;
        }

        $room = Room::create($roomData);

        if ($org) {
            return redirect()->route('org.rooms.index')->with('success', 'Classroom created successfully!');
        }

        return redirect()->route('rooms.index')->with('success', 'Classroom created successfully!');
    }

    // Invite a teacher to the room
    public function inviteTeacher(Request $request, Room $room)
    {
        // Only admin or org_admin can invite teachers
        if (!auth()->user()->isAdmin() && auth()->user()->role !== 'org_admin') {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email',
        ]);


        $teacher = User::where('email', $request->email)->first();
        if (!$teacher) {
            return back()->with('error', 'This email is not yet registered.');
        }

        if (!$teacher->isTeacher()) {
            return back()->with('error', 'This user is not registered as a teacher.');
        }

        $room->update(['tutor_id' => $teacher->id]);

        // NOTE: We deliberately do NOT overwrite the teacher's organization_id here.
        // A teacher can be invited to rooms across multiple organizations.
        // Access is controlled by tutor_id on the room, not by organization_id on the user.

        return back()->with('success', 'Teacher successfully assigned to the classroom.');
    }

    // 4. Join room as student (Request to Join)
    public function join($roomOrId, $orgSlug = null)
    {
        $user = auth()->user();

        if (!($roomOrId instanceof Room)) {
            if ($orgSlug) {
                $orgToInitialize = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
                tenancy()->initialize($orgToInitialize);
                $room = Room::findOrFail($roomOrId);
            } else {
                $room = Room::findOrFail($roomOrId);
            }
        } else {
            $room = $roomOrId;
        }

        if ($room->tutor_id === auth()->id()) {
            if (isset($orgToInitialize)) tenancy()->end();
            return back()->with('error', 'You are already the tutor for this classroom.');
        }

        // Use allStudents() (no status filter) so syncWithoutDetaching works correctly
        $room->allStudents()->syncWithoutDetaching([auth()->id() => ['status' => 'pending']]);

        if ($room->tutor_id) {
            \App\Models\Notification::create([
                'user_id' => $room->tutor_id,
                'type' => 'join_request',
                'title' => 'New Join Request',
                'message' => auth()->user()->name . ' requested to join your classroom "' . $room->subject_name . '".',
                'icon' => '👋'
            ]);
        }

        if (isset($orgToInitialize)) tenancy()->end();

        return back()->with('success', 'Your request to join has been sent and is pending approval.');
    }

    public function approveJoin($roomId, $studentId)
    {
        $isTenantContext = function_exists('tenant') && tenant();
        $room = $isTenantContext ? Room::findOrFail($roomId) : Room::findOrFail($roomId);

        $isTutor = $room->tutor_id == auth()->id();
        $isAdmin = auth()->user()->isAdmin() ||
                   auth()->user()->role === 'org_admin';

        if (!$isTutor && !$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        // Use allStudents() (no status filter) so updateExistingPivot can find the 'pending' row
        $room->allStudents()->updateExistingPivot($studentId, ['status' => 'approved']);

        \App\Models\Notification::create([
            'user_id' => $studentId,
            'type' => 'join_approved',
            'title' => 'Request Approved',
            'message' => 'Your request to join "' . $room->subject_name . '" has been approved.',
            'icon' => '✅'
        ]);

        return back()->with('success', 'Student join request approved.');
    }

    public function rejectJoin($roomId, $studentId)
    {
        $isTenantContext = function_exists('tenant') && tenant();
        $room = $isTenantContext ? Room::findOrFail($roomId) : Room::findOrFail($roomId);

        $isTutor = $room->tutor_id == auth()->id();
        $isAdmin = auth()->user()->isAdmin() ||
                   auth()->user()->role === 'org_admin';

        if (!$isTutor && !$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        // Use allStudents() (no status filter) so detach can find the 'pending' row
        $room->allStudents()->detach($studentId);

        \App\Models\Notification::create([
            'user_id' => $studentId,
            'type' => 'join_rejected',
            'title' => 'Request Declined',
            'message' => 'Your request to join "' . $room->subject_name . '" has been declined.',
            'icon' => '❌'
        ]);

        return back()->with('success', 'Student join request rejected.');
    }

    public function removeStudent(Room $room, $studentId)
    {
        $isTutor = $room->tutor_id === auth()->id();
        $isAdmin = auth()->user()->isAdmin() || auth()->user()->role === 'org_admin';

        if (!$isTutor && !$isAdmin) {
            abort(403, 'Unauthorized action.');
        }

        // Detach the student from the room (works for any status)
        $room->allStudents()->detach($studentId);

        \App\Models\Notification::create([
            'user_id' => $studentId,
            'type'    => 'removed_from_room',
            'title'   => 'Removed from Classroom',
            'message' => 'You have been removed from the classroom "' . $room->subject_name . '".',
            'icon'    => '🚫'
        ]);

        return back()->with('success', 'Student has been removed from the classroom.');
    }

    // 4.5. Seamless enter for multi-tenant setup using magic login
    public function enterTenant($roomId, $orgSlug)
    {
        $user = auth()->user();
        
        // Generate magic token to pass auth across domains without relying on session cookies
        $token = \Illuminate\Support\Str::random(40);
        \Illuminate\Support\Facades\Cache::put('magic_login_' . $token, $user->id, now()->addSeconds(120));
        
        $centralDomains = config('tenancy.central_domains', ['localhost']);
        $centralDomain  = $centralDomains[0];
        $port           = request()->getPort();
        $portStr        = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
        $scheme         = request()->getScheme();
        
        $tenantBase = $scheme . '://' . $orgSlug . '.' . $centralDomain . $portStr;
        
        return redirect($tenantBase . '/magic-login/' . $token . '?redirect=/rooms/' . $roomId);
    }

    // 5. I-pakita ang detalye sa usa ka Room
    public function show(Room $room)
    {
        $org = $this->resolveOrg();
        $isTenantContext = function_exists('tenant') && tenant();
        $isTutor = $room->tutor_id === auth()->id();
        $isStudent = $room->students()->where('user_id', auth()->id())->exists();

        if ($org && !$isTenantContext) {
            // Central domain org-slug context: verify room belongs to org
            if ($room->organization_id !== $org->id) {
                abort(404);
            }
            // Tutors are always allowed regardless of their own organization_id.
            // Students and other users must be in the org or already enrolled.
            if (!$isTutor && !$isStudent && !auth()->user()->isAdmin() && auth()->user()->organization_id !== $org->id) {
                abort(403, 'Unauthorized access to this classroom.');
            }
        }

        // isAdmin: on tenant context, org_admin + any user in this tenant DB qualifies
        if ($isTenantContext) {
            $isAdmin = auth()->user()->isAdmin() || auth()->user()->role === 'org_admin';
        } else {
            $isAdmin = auth()->user()->isAdmin() ||
                       (auth()->user()->role === 'org_admin' && auth()->user()->organization_id === ($room->organization_id ?? null));
        }

        // Guard: only tutor, admin, or enrolled student may view the room.
        // Tutors are always allowed — they are the explicitly invited teacher for this room.
        // This check must happen AFTER isTutor is set so tutors are never blocked.
        if (!$isTutor && !$isAdmin && !$isStudent) {
            $redirectRoute = $isTenantContext ? '/rooms' : ($org ? route('org.rooms.index') : route('rooms.index'));
            return redirect($redirectRoute)->with('error', 'Please join the classroom first.');
        }

        $files = $room->files()->with('purchases')->get();
        $activities = $room->activities()->with(['submissions' => function($q) {
            $q->where('student_id', auth()->id());
        }])->latest()->get();

        // For tutor, get all submissions
        if ($isTutor) {
            $activities = $room->activities()->with('submissions.student')->latest()->get();
        }

        $pendingStudents = collect();
        if ($isAdmin || $isTutor) {
            $pendingStudents = $room->pendingStudents()->get();
        }

        // Set routes for view — tenant routes don't take org slug param (subdomain handles it)
        $updateRoute       = $org ? route('org.rooms.update', $room->id) : route('rooms.update', $room->id);
        $inviteRoute       = $org ? route('org.rooms.invite', $room->id) : route('rooms.invite', $room->id);
        $inviteTeacherRoute = $org ? route('org.rooms.invite-teacher', $room->id) : route('rooms.invite-teacher', $room->id);
        $uploadRoute       = $org ? route('org.rooms.upload-file', $room->id) : route('rooms.upload-file', $room->id);
        $purchaseRouteTemplate = $org
            ? route('org.rooms.purchase-file', [$room->id, ':file_id'])
            : route('rooms.purchase-file', [$room->id, ':file_id']);
        $approveRouteTemplate = $org
            ? route('org.rooms.approve-purchase', [$room->id, ':purchase_id'])
            : route('rooms.approve-purchase', [$room->id, ':purchase_id']);
        $activityStoreRoute = $org ? route('org.rooms.activities.store', $room->id) : route('rooms.activities.store', $room->id);

        return view('rooms.show', compact('room', 'org', 'files', 'activities', 'isTutor', 'isAdmin', 'isStudent', 'pendingStudents', 'updateRoute', 'inviteRoute', 'inviteTeacherRoute', 'uploadRoute', 'purchaseRouteTemplate', 'approveRouteTemplate', 'activityStoreRoute'));
    }

    public function update(Request $request, Room $room)
    {
        $isTutor = $room->tutor_id === auth()->id();
        $isAdmin = auth()->user()->isAdmin() || (auth()->user()->role === 'org_admin' && auth()->user()->organization_id === $room->organization_id);
        
        if (!$isTutor && !$isAdmin) {
            abort(403);
        }

        $request->validate([
            'subject_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:5120',
        ]);

        $room->subject_name = $request->subject_name;
        $room->description = $request->description;

        if ($request->hasFile('cover_photo')) {
            $room->cover_photo = $request->file('cover_photo')->store('rooms/covers', 'central_public');
        }

        $room->save();

        return back()->with('success', 'Classroom updated successfully!');
    }

    public function inviteStudent(Request $request, Room $room)
    {
        $isTutor = $room->tutor_id === auth()->id();
        $isAdmin = auth()->user()->isAdmin() || (auth()->user()->role === 'org_admin' && auth()->user()->organization_id === $room->organization_id);
        
        if (!$isTutor && !$isAdmin) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $student = User::where('email', $request->email)->first();
        if (!$student) {
            return back()->with('error', 'This email is not yet registered.');
        }

        if ($room->students()->where('user_id', $student->id)->exists()) {
            return back()->with('error', 'This student is already in this classroom.');
        }

        $room->students()->attach($student->id);

        // Also add student to the organization if the room belongs to one
        $orgId = function_exists('tenant') && tenant() ? tenant('id') : $room->organization_id;
        if ($orgId && !$student->organization_id) {
            $student->update(['organization_id' => $orgId]);
        }

        \App\Models\Notification::create([
            'user_id' => $student->id,
            'type' => 'room_invited',
            'title' => 'Added to Classroom',
            'message' => 'You have been added to the classroom "' . $room->subject_name . '".',
            'link' => route('dashboard'),
            'icon' => '🎓'
        ]);

        return back()->with('success', 'Student successfully added to the classroom.');
    }

    // 6. Pag-upload og file para sa classroom (Only Teacher)
    public function uploadFile(Request $request, Room $room)
    {
        // ONLY teacher can upload as per request
        if ($room->tutor_id !== auth()->id()) {
            abort(403, 'Only the assigned teacher can upload files.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', 
            'price' => 'required|numeric|min:0',
        ]);

        $path = $request->file('file')->store('rooms/files', 'public');

        $room->files()->create([
            'title' => $request->title,
            'file_path' => $path,
            'price' => $request->price,
        ]);

        return back()->with('success', 'File uploaded successfully!');
    }

    // Announcements Logic
    public function storeAnnouncement(Request $request, Room $room)
    {
        if ($room->tutor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $room->announcements()->create([
            'content' => $request->content,
        ]);

        return back()->with('success', 'Announcement posted successfully!');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $room = $announcement->room;
        if ($room->tutor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $announcement->update([
            'content' => $request->content,
        ]);

        return back()->with('success', 'Announcement updated successfully!');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        $room = $announcement->room;
        if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully!');
    }

    // Activities Logic
    public function storeActivity(Request $request, Room $room)
    {
        if ($room->tutor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'link' => 'nullable|url|max:255',
            'allow_late_submissions' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'deadline', 'link']);
        $data['allow_late_submissions'] = $request->has('allow_late_submissions');
        
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('activities', 'public');
        }

        $room->activities()->create($data);

        return back()->with('success', 'Activity created successfully!');
    }

    public function updateActivity(Request $request, Activity $activity)
    {
        $room = $activity->room;
        if ($room->tutor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url|max:255',
            'allow_late_submissions' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'deadline', 'link']);
        $data['allow_late_submissions'] = $request->has('allow_late_submissions');

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($activity->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($activity->file_path);
            }
            $data['file_path'] = $request->file('file')->store('activities', 'public');
        }

        $activity->update($data);

        return back()->with('success', 'Activity updated successfully!');
    }

    public function destroyActivity(Activity $activity)
    {
        $room = $activity->room;
        if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete associated files from storage
        if ($activity->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($activity->file_path);
        }

        foreach($activity->submissions as $sub) {
            if ($sub->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($sub->file_path);
            }
        }

        $activity->delete();

        return back()->with('success', 'Activity deleted successfully!');
    }

    public function submitActivity(Request $request, Activity $activity)
    {
        $room = $activity->room;
        if (!$room->students()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }

        if ($activity->deadline && \Carbon\Carbon::now()->isAfter($activity->deadline) && !$activity->allow_late_submissions) {
            return back()->with('error', 'The deadline for this activity has passed and late submissions are not allowed.');
        }

        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $path = $request->file('file')->store('submissions', 'public');

        Submission::updateOrCreate(
            ['activity_id' => $activity->id, 'student_id' => auth()->id()],
            ['file_path' => $path]
        );

        return back()->with('success', 'Activity submitted successfully!');
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        if ($submission->activity->room->tutor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'grade' => 'required|string|max:255',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
        ]);

        $isTenantContext = function_exists('tenant') && tenant();
        $roomLink = $isTenantContext 
            ? route('rooms.enter', ['room' => $submission->activity->room_id, 'org_slug' => tenant('slug')]) 
            : route('rooms.show', $submission->activity->room_id);

        \App\Models\Notification::create([
            'user_id' => $submission->user_id,
            'type' => 'submission_graded',
            'title' => 'Activity Graded',
            'message' => 'Your submission for "' . $submission->activity->title . '" has been graded: ' . $request->grade,
            'link' => $roomLink,
            'icon' => '⭐'
        ]);

        return back()->with('success', 'Submission graded successfully!');
    }

    // 7. Pag-purchase og file
    public function purchaseFile(Request $request, Room $room, $file)
    {
        \Illuminate\Support\Facades\Log::info('PURCHASE_FILE_HIT', [
            'room'    => $room->id,
            'file'    => $file,
            'user'    => auth()->id(),
            'has_file' => $request->hasFile('proof_of_payment'),
        ]);

        $fileModel = \App\Models\File::findOrFail($file);
        
        $request->validate([
            'proof_of_payment' => 'required|image|max:5120',
        ]);

        $path = $request->file('proof_of_payment')->store('proofs', 'public');

        \App\Models\FilePurchase::create([
            'user_id'          => auth()->id(),
            'file_id'          => $fileModel->id,
            'status'           => 'pending',
            'proof_of_payment' => $path,
        ]);

        // Notify the teacher
        if ($fileModel->room->tutor_id) {
            $isTenantContext = function_exists('tenant') && tenant();
            $roomLink = $isTenantContext
                ? route('rooms.enter', ['room' => $fileModel->room_id, 'org_slug' => tenant('slug')])
                : route('rooms.show', $fileModel->room_id);

            \App\Models\Notification::create([
                'user_id' => $fileModel->room->tutor_id,
                'type'    => 'material_purchased',
                'title'   => 'New Material Purchase',
                'message' => auth()->user()->name . ' has submitted payment for "' . $fileModel->title . '".',
                'link'    => $roomLink,
                'icon'    => '💸'
            ]);
        }

        return request()->wantsJson()
            ? response()->json(['message' => 'Proof of payment submitted successfully.'], 200)
            : back()->with('success', 'Your proof of payment has been submitted. Please wait for the tutor\'s confirmation.');
    }

    public function serveTenantProof($orgSlug, $path)
    {
        // Must be auth'd and either an admin or the tutor for security
        // Since this is just proof of payment, we can let admins and teachers see it.
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            abort(403);
        }

        // Construct the full path based on FilesystemTenancyBootstrapper configuration
        // Default suffix is 'tenant' + slug.
        $tenantPrefix = config('tenancy.filesystem.suffix_base', 'tenant') . $orgSlug;
        $fullPath = storage_path("{$tenantPrefix}/app/public/{$path}");

        if (!file_exists($fullPath)) {
            abort(404, 'Proof of payment file not found.');
        }

        return response()->file($fullPath);
    }

    public function serveTenantProofOrg($path)
    {
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isTeacher())) {
            abort(403);
        }

        $fullPath = storage_path("app/public/{$path}");

        if (!file_exists($fullPath)) {
            abort(404, 'Proof of payment file not found.');
        }

        return response()->file($fullPath);
    }

    protected function authorizeFileAccess(File $file)
    {
        $room = $file->room;

        if ($room->tutor_id === auth()->id() || auth()->user()->isAdmin()) {
            return $room;
        }

        if (!$room->students()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized access to this file.');
        }

        if (!$file->isPurchasedBy(auth()->id())) {
            abort(403, 'Please purchase this file to access it.');
        }

        return $room;
    }

    public function previewFile($file_id)
    {
        $orgSlug = request('org_slug');
        if ($orgSlug) {
            $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
            tenancy()->initialize($org);
            $file = File::findOrFail($file_id);
            $this->authorizeFileAccess($file);
            $response = response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($file->file_path));
            tenancy()->end();
            return $response;
        }

        $file = File::findOrFail($file_id);
        $this->authorizeFileAccess($file);
        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($file->file_path));
    }

    public function previewFileOrg(File $file)
    {
        $this->authorizeFileAccess($file);
        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($file->file_path));
    }

    public function downloadFile($file_id)
    {
        $orgSlug = request('org_slug');
        if ($orgSlug) {
            $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
            tenancy()->initialize($org);
            $file = File::findOrFail($file_id);
            $this->authorizeFileAccess($file);
            $response = Storage::disk('public')->download($file->file_path);
            tenancy()->end();
            return $response;
        }

        $file = File::findOrFail($file_id);
        $this->authorizeFileAccess($file);
        return Storage::disk('public')->download($file->file_path);
    }

    public function previewActivityAttachment(Activity $activity)
    {
        $orgSlug = request('org_slug');
        if ($orgSlug) {
            $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
            tenancy()->initialize($org);
            $activity = Activity::findOrFail($activity->id);
            // Must be tutor, admin, or enrolled student
            $room = $activity->room;
            if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && !$room->students()->where('user_id', auth()->id())->exists()) {
                abort(403);
            }
            $response = response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($activity->file_path));
            tenancy()->end();
            return $response;
        }

        $room = $activity->room;
        if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && !$room->students()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }
        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($activity->file_path));
    }

    public function previewActivityAttachmentOrg(Activity $activity)
    {
        $room = $activity->room;
        if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && auth()->user()->role !== 'org_admin' && !$room->students()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }
        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($activity->file_path));
    }

    public function downloadActivityAttachment(Activity $activity)
    {
        $orgSlug = request('org_slug');
        if ($orgSlug) {
            $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
            tenancy()->initialize($org);
            $activity = Activity::findOrFail($activity->id);
            // Must be tutor, admin, or enrolled student
            $room = $activity->room;
            if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && !$room->students()->where('user_id', auth()->id())->exists()) {
                abort(403);
            }
            $response = Storage::disk('public')->download($activity->file_path);
            tenancy()->end();
            return $response;
        }

        $room = $activity->room;
        if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && !$room->students()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }
        return Storage::disk('public')->download($activity->file_path);
    }

    public function downloadActivityAttachmentOrg(Activity $activity)
    {
        $room = $activity->room;
        if ($room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && auth()->user()->role !== 'org_admin' && !$room->students()->where('user_id', auth()->id())->exists()) {
            abort(403);
        }
        return Storage::disk('public')->download($activity->file_path);
    }

    public function previewSubmission(Submission $submission)
    {
        $orgSlug = request('org_slug');
        if ($orgSlug) {
            $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
            tenancy()->initialize($org);
            $submission = Submission::findOrFail($submission->id);
            if ($submission->activity->room->tutor_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
            $response = response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($submission->file_path));
            tenancy()->end();
            return $response;
        }
        if ($submission->activity->room->tutor_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($submission->file_path));
    }

    public function downloadSubmission(Submission $submission)
    {
        $orgSlug = request('org_slug');
        if ($orgSlug) {
            $org = \App\Models\Organization::where('slug', $orgSlug)->firstOrFail();
            tenancy()->initialize($org);
            $submission = Submission::findOrFail($submission->id);
            if ($submission->activity->room->tutor_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
            $response = Storage::disk('public')->download($submission->file_path);
            tenancy()->end();
            return $response;
        }
        if ($submission->activity->room->tutor_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);
        return Storage::disk('public')->download($submission->file_path);
    }

    public function previewSubmissionOrg(Submission $submission)
    {
        if ($submission->activity->room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && auth()->user()->role !== 'org_admin') abort(403);
        return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($submission->file_path));
    }

    public function downloadSubmissionOrg(Submission $submission)
    {
        if ($submission->activity->room->tutor_id !== auth()->id() && !auth()->user()->isAdmin() && auth()->user()->role !== 'org_admin') abort(403);
        return Storage::disk('public')->download($submission->file_path);
    }

    public function downloadFileOrg(File $file)
    {
        $this->authorizeFileAccess($file);
        return Storage::disk('public')->download($file->file_path);
    }

    // 8. Pag-approve sa purchase (Para sa Tutor lamang sumala sa roles.png)
    public function approvePurchase(Room $room, $purchase_id)
    {
        // Only the assigned teacher (tutor) can approve payments for their files
        if ($room->tutor_id !== auth()->id()) {
            abort(403, 'Only the assigned teacher can verify payments.');
        }

        $purchase = \App\Models\FilePurchase::findOrFail($purchase_id);
        $purchase->update(['status' => 'completed']);

        // Notify the student
        \App\Models\Notification::create([
            'user_id' => $purchase->user_id,
            'type' => 'purchase_approved',
            'title' => 'Purchase Approved',
            'message' => 'Your purchase of "' . $purchase->file->title . '" has been verified. You can now access the file.',
            'link' => route('dashboard'),
            'icon' => '🔓'
        ]);

        return back()->with('success', 'Purchase approved! The student can now access the file.');
    }
}
