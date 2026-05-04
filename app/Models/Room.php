<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (function_exists('tenant') && tenant()) {
            $this->setConnection('tenant');
        }
    }

    protected $fillable = [
        'organization_id',
        'tutor_id',
        'subject_name',
        'description',
        'cover_photo',
        'fee',
        'room_link',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Returns the correct public URL for the room's cover photo.
     *
     * IMPORTANT: We use base_path() instead of storage_path() because
     * FilesystemTenancyBootstrapper overrides storage_path() in tenant context,
     * making it point to the tenant-isolated storage directory. base_path()
     * always resolves to the application root, so we can safely check central
     * public storage regardless of the active tenant.
     */
    public function coverPhotoUrl(): ?string
    {
        if (!$this->cover_photo) {
            return null;
        }

        // Use base_path so we always target central storage (not tenant-scoped storage_path)
        $centralStoragePath = base_path('storage/app/public/' . $this->cover_photo);

        if (file_exists($centralStoragePath)) {
            // Serve via central public/storage symlink
            return url('storage/' . $this->cover_photo);
        }

        // Fallback: legacy file stored in tenant-isolated storage
        return tenant_asset($this->cover_photo);
    }

    // Sa database migration, ang column name kay tutor_id (o user_id base sa migration kaniadto)
    // Atong i-align sa saktong migration field
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function students()
    {
        $tenantDb = config('database.connections.tenant.database');
        $table = $tenantDb ? "{$tenantDb}.room_user" : 'room_user';
        return $this->belongsToMany(User::class, $table);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
