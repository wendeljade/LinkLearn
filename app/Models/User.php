<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Organization;
use App\Models\Room;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $connection = 'central';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'organization_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
public function organization()
{
    // Explicit FK + owner key needed because Organization uses slug as Eloquent PK.
    // Without this, belongsTo(Organization::class) would do WHERE slug = organization_id
    // (comparing integer organization_id to slug string) which always returns null.
    return $this->belongsTo(Organization::class, 'organization_id', 'id');
}

public function notifications()
{
    return $this->hasMany(Notification::class);
}

public function rooms()
{
    return $this->hasMany(Room::class, 'tutor_id');
}

public function joinedRooms()
{
    $tenantDb = config('database.connections.tenant.database');
    $table = $tenantDb ? "{$tenantDb}.room_user" : 'room_user';
    return $this->belongsToMany(Room::class, $table)
                ->withPivot('status')
                ->wherePivot('status', 'approved');
}

/**
 * Get all classrooms this user has joined across ALL tenant organizations.
 *
 * Because each tenant has its own isolated database, we directly query
 * each tenant DB's room_user and rooms tables using cross-database SQL.
 * Returns a Collection of stdClass objects with room data + org info.
 */
public function allJoinedRooms($status = 'open'): \Illuminate\Support\Collection
{
    $allRooms  = collect();
    $prefix    = config('tenancy.database.prefix', 'linklearn_org_');
    $orgs      = Organization::where('status', 'active')->get();

    foreach ($orgs as $org) {
        $tenantDb = $prefix . $org->slug;

        // Skip if the tenant database doesn't exist yet
        $exists = \Illuminate\Support\Facades\DB::select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$tenantDb]
        );
        if (empty($exists)) {
            continue;
        }

        try {
            $rows = \Illuminate\Support\Facades\DB::select(
                "SELECT r.*,
                        ? AS org_slug, ? AS org_name, ? AS org_id,
                        ru.status AS join_status
                 FROM `{$tenantDb}`.`rooms` r
                 INNER JOIN `{$tenantDb}`.`room_user` ru ON ru.room_id = r.id
                 WHERE ru.user_id = ? AND r.status = ?",
                [$org->slug, $org->name, $org->id, $this->id, $status]
            );

            foreach ($rows as $row) {
                // Skip pending memberships for 'My Classrooms'
                if ($row->join_status !== 'approved') {
                    continue;
                }

                // Hydrate Eloquent model so views can use methods like coverPhotoUrl()
                $attributes = (array) $row;
                $roomModel = (new Room())->newFromBuilder($attributes);
                
                // Attach custom properties
                $roomModel->org_slug = $org->slug;
                $roomModel->org_name = $org->name;
                $roomModel->org_id = $org->id;
                $roomModel->organization = $org;
                
                // Flag that the current user is a member (avoids cross-db relation queries)
                $roomModel->is_member = true;
                
                // Eager load the tutor from the central database explicitly
                if ($roomModel->tutor_id) {
                    $tutor = \App\Models\User::on('central')->find($roomModel->tutor_id);
                    $roomModel->setRelation('tutor', $tutor);
                }

                $allRooms->push($roomModel);
            }
        } catch (\Exception $e) {
            // Tenant DB may not have rooms/room_user tables — skip silently
            \Illuminate\Support\Facades\Log::warning('allJoinedRooms: skipping tenant ' . $org->slug, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $allRooms;
}

public function allTaughtRooms($status = 'open'): \Illuminate\Support\Collection
{
    $allRooms = collect();
    $prefix   = config('tenancy.database.prefix', 'linklearn_org_');

    // 1. Get rooms from the central database where this user is tutor
    $centralRooms = Room::on('central')
        ->where('tutor_id', $this->id)
        ->where('status', 'open')
        ->get();
    foreach ($centralRooms as $room) {
        $room->is_tutor = true;
        $allRooms->push($room);
    }

    // 2. Loop through ALL active organizations and query their tenant databases
    $orgs = \App\Models\Organization::where('status', 'active')->get();

    foreach ($orgs as $org) {
        // Correctly derive the tenant DB name per-org using the configured prefix
        $tenantDb = $prefix . $org->slug;

        $exists = \Illuminate\Support\Facades\DB::select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$tenantDb]
        );
        if (empty($exists)) continue;

        try {
            // Find rooms where this user is the assigned tutor, include student count
            $rows = \Illuminate\Support\Facades\DB::select(
                "SELECT r.*, 
                        (SELECT COUNT(*) FROM `{$tenantDb}`.`room_user` ru WHERE ru.room_id = r.id) AS student_count
                 FROM `{$tenantDb}`.`rooms` r
                 WHERE r.tutor_id = ? AND r.status = ?",
                [$this->id, $status]
            );

            foreach ($rows as $row) {
                $attributes = (array) $row;
                $roomModel = (new Room())->newFromBuilder($attributes);

                // Attach org context so views can build the correct magic-login entry URL
                $roomModel->org_slug     = $org->slug;
                $roomModel->org_name     = $org->name;
                $roomModel->org_id       = $org->id;
                $roomModel->organization = $org;
                $roomModel->is_tutor     = true;
                $roomModel->is_member    = true; // tutors are always members of their rooms

                // Load tutor from central DB explicitly
                if ($roomModel->tutor_id) {
                    $tutor = \App\Models\User::on('central')->find($roomModel->tutor_id);
                    $roomModel->setRelation('tutor', $tutor);
                }

                $allRooms->push($roomModel);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('allTaughtRooms: skipping tenant ' . $org->slug, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $allRooms;
}

/**
 * Get ALL open classrooms across ALL organizations, attaching the current user's join_status if any.
 */
public function allExploreRooms(): \Illuminate\Support\Collection
{
    $allRooms = collect();
    $prefix   = config('tenancy.database.prefix', 'linklearn_org_');

    // 1. Get rooms from the central database
    $centralRooms = Room::on('central')
        ->where('status', 'open')
        ->with('tutor')
        ->get();
        
    foreach ($centralRooms as $room) {
        $pivot = $room->students()->wherePivot('user_id', $this->id)->first();
        $room->join_status = $pivot ? $pivot->pivot->status : null;
        $room->is_member = ($room->join_status === 'approved' || $room->tutor_id === $this->id);
        
        // Only show rooms the user has NOT joined yet
        if (!$room->is_member) {
            $allRooms->push($room);
        }
    }

    // 2. Loop through ALL active organizations and query their tenant databases
    $orgs = \App\Models\Organization::where('status', 'active')->get();

    foreach ($orgs as $org) {
        $tenantDb = $prefix . $org->slug;

        $exists = \Illuminate\Support\Facades\DB::select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$tenantDb]
        );
        if (empty($exists)) continue;

        try {
            // Find all open rooms. LEFT JOIN room_user to get the current user's status.
            $rows = \Illuminate\Support\Facades\DB::select(
                "SELECT r.*, 
                        ? AS org_slug, ? AS org_name, ? AS org_id,
                        ru.status AS join_status
                 FROM `{$tenantDb}`.`rooms` r
                 LEFT JOIN `{$tenantDb}`.`room_user` ru ON ru.room_id = r.id AND ru.user_id = ?
                 WHERE r.status = 'open'",
                [$org->slug, $org->name, $org->id, $this->id]
            );

            foreach ($rows as $row) {
                $attributes = (array) $row;
                $roomModel = (new Room())->newFromBuilder($attributes);

                $roomModel->org_slug     = $org->slug;
                $roomModel->org_name     = $org->name;
                $roomModel->org_id       = $org->id;
                $roomModel->organization = $org;
                $roomModel->join_status  = $row->join_status;
                $roomModel->is_member    = ($roomModel->join_status === 'approved' || $roomModel->tutor_id === $this->id);

                // Only show rooms the user has NOT joined yet
                if ($roomModel->is_member) {
                    continue;
                }

                // Load tutor from central DB explicitly
                if ($roomModel->tutor_id) {
                    $tutor = \App\Models\User::on('central')->find($roomModel->tutor_id);
                    $roomModel->setRelation('tutor', $tutor);
                }

                // Don't add if already fetched via central (avoids duplication if sync exists)
                if (!$allRooms->contains('id', $roomModel->id)) {
                    $allRooms->push($roomModel);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('allExploreRooms: skipping tenant ' . $org->slug, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $allRooms;
}

public function allPendingJoinRequests()
{
    $allRequests = collect();
    $centralDb = \Illuminate\Support\Facades\DB::connection('central')->getDatabaseName();

    // Loop through ALL active organizations and query their tenant databases
    $orgs = \App\Models\Organization::where('status', 'active')->get();
    $prefix = config('tenancy.database.prefix', 'linklearn_org_');

    foreach ($orgs as $org) {
        $tenantDb = $prefix . $org->slug;
        $exists = \Illuminate\Support\Facades\DB::connection('central')->select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$tenantDb]
        );
        if (empty($exists)) continue;

        try {
            $rows = \Illuminate\Support\Facades\DB::connection('central')->select(
                "SELECT ru.room_id, ru.user_id, ru.status,
                        r.subject_name AS room_name,
                        u.name AS student_name, u.email AS student_email, u.avatar AS student_profile,
                        ? AS org_slug, ? AS org_name, ? AS org_id
                 FROM `{$tenantDb}`.`room_user` ru
                 INNER JOIN `{$tenantDb}`.`rooms` r ON r.id = ru.room_id
                 LEFT JOIN `{$centralDb}`.`users` u ON u.id = ru.user_id
                 WHERE r.tutor_id = ? AND ru.status = 'pending'",
                [$org->slug, $org->name, $org->id, $this->id]
            );

            foreach ($rows as $row) {
                $allRequests->push((object) $row);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('allPendingJoinRequests: skipping tenant ' . $org->slug, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $allRequests;
}

public function allPendingRequests()
{
    $allRequests = collect();

    // 1. Get requests from central database (if any)
    $centralRequests = \App\Models\FilePurchase::where('status', 'pending')
        ->whereHas('file', function ($query) {
            $query->whereIn('room_id', $this->rooms()->where('tutor_id', $this->id)->pluck('id'));
        })
        ->with(['user', 'file.room'])
        ->get();
        
    foreach ($centralRequests as $req) {
        $allRequests->push($req);
    }

    // 2. Loop through ALL active organizations and query their tenant databases
    $orgs = \App\Models\Organization::where('status', 'active')->get();
    $prefix = config('tenancy.database.prefix', 'linklearn_org_');

    foreach ($orgs as $org) {
        $tenantDb = $prefix . $org->slug;
        $exists = \Illuminate\Support\Facades\DB::select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$tenantDb]
        );
        if (empty($exists)) continue;

        try {
            // Raw query to get pending purchases for rooms where this user is the tutor
            $rows = \Illuminate\Support\Facades\DB::select(
                "SELECT fp.*,
                        ? AS org_slug, ? AS org_name, ? AS org_id
                 FROM `{$tenantDb}`.`file_purchases` fp
                 INNER JOIN `{$tenantDb}`.`files` f ON f.id = fp.file_id
                 INNER JOIN `{$tenantDb}`.`rooms` r ON r.id = f.room_id
                 WHERE r.tutor_id = ? AND fp.status = 'pending'",
                [$org->slug, $org->name, $org->id, $this->id]
            );

            foreach ($rows as $row) {
                $attributes = (array) $row;
                $purchaseModel = (new \App\Models\FilePurchase())->newFromBuilder($attributes);
                
                // Set custom org properties
                $purchaseModel->org_slug = $org->slug;
                
                // Hydrate the relations explicitly from central & tenant
                // The user who purchased is in central
                $purchaseModel->setRelation('user', \App\Models\User::find($row->user_id));
                
                // Hydrate the file
                $fileData = \Illuminate\Support\Facades\DB::selectOne("SELECT * FROM `{$tenantDb}`.`files` WHERE id = ?", [$row->file_id]);
                if ($fileData) {
                    $fileModel = (new \App\Models\File())->newFromBuilder((array) $fileData);
                    
                    // Hydrate the room
                    $roomData = \Illuminate\Support\Facades\DB::selectOne("SELECT * FROM `{$tenantDb}`.`rooms` WHERE id = ?", [$fileData->room_id]);
                    if ($roomData) {
                        $roomModel = (new \App\Models\Room())->newFromBuilder((array) $roomData);
                        $roomModel->org_slug = $org->slug; // needed for route generation
                        $fileModel->setRelation('room', $roomModel);
                    }
                    
                    $purchaseModel->setRelation('file', $fileModel);
                }

                $allRequests->push($purchaseModel);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('allPendingRequests: skipping tenant ' . $org->slug, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $allRequests;
}

/**
 * Get all completed file purchases for this student across ALL tenant databases.
 * Because file_purchases live in tenant DBs, we cross-query each org's database.
 */
public function allPurchasedFiles(): \Illuminate\Support\Collection
{
    $allPurchases = collect();
    $prefix       = config('tenancy.database.prefix', 'linklearn_org_');
    $orgs         = \App\Models\Organization::where('status', 'active')->get();

    foreach ($orgs as $org) {
        $tenantDb = $prefix . $org->slug;

        $exists = \Illuminate\Support\Facades\DB::select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$tenantDb]
        );
        if (empty($exists)) continue;

        try {
            $rows = \Illuminate\Support\Facades\DB::select(
                "SELECT fp.*, f.title AS file_title, f.price AS file_price,
                        f.file_path, r.subject_name AS room_name,
                        ? AS org_slug, ? AS org_name
                 FROM `{$tenantDb}`.`file_purchases` fp
                 INNER JOIN `{$tenantDb}`.`files` f ON f.id = fp.file_id
                 INNER JOIN `{$tenantDb}`.`rooms` r ON r.id = f.room_id
                 WHERE fp.user_id = ? AND fp.status = 'completed'",
                [$org->slug, $org->name, $this->id]
            );

            foreach ($rows as $row) {
                $attrs = (array) $row;

                // Hydrate FilePurchase model
                $purchaseModel = (new \App\Models\FilePurchase())->newFromBuilder($attrs);
                $purchaseModel->org_slug = $org->slug;

                // Hydrate File model
                $fileModel = (new \App\Models\File())->newFromBuilder([
                    'id'        => $row->file_id,
                    'room_id'   => null,
                    'title'     => $row->file_title,
                    'file_path' => $row->file_path,
                    'price'     => $row->file_price,
                ]);

                // Hydrate Room model
                $roomModel = (new \App\Models\Room())->newFromBuilder([
                    'subject_name' => $row->room_name,
                ]);
                $roomModel->org_slug = $org->slug;
                $roomModel->org_name = $org->name;

                $fileModel->setRelation('room', $roomModel);
                $purchaseModel->setRelation('file', $fileModel);

                $allPurchases->push($purchaseModel);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('allPurchasedFiles: skipping tenant ' . $org->slug, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $allPurchases;
}

// Paghimo og helper functions para dali i-check ang role sa code
public function isSuperAdmin() { return $this->role === 'super_admin'; }
public function isAdmin() { return in_array($this->role, ['admin', 'org_admin', 'super_admin']); }
public function isTeacher() { return $this->role === 'teacher' || $this->role === 'tutor'; }
public function isStudent() { return $this->role === 'student'; }
}