<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Domain as DomainModel;

class Organization extends BaseTenant implements TenantWithDatabase
{
    use HasFactory, HasDatabase, HasDomains;

    /**
     * Always use the central MySQL connection.
     */
    protected $connection = 'central';

    /**
     * Use the organizations table.
     */
    protected $table = 'organizations';

    /**
     * slug is the Eloquent primary key so Organization::find('testschool') works.
     * stancl/tenancy calls Model::find($tenantId) during subdomain resolution,
     * and tenant_id in the domains table stores the slug string.
     * DB names become: linklearn_org_{slug}  (e.g. linklearn_org_testschool)
     */
    protected $primaryKey = 'slug';
    public    $incrementing = false;
    protected $keyType     = 'string';

    /**
     * Override GeneratesIds::getIncrementing() which returns true when
     * UniqueIdentifierGenerator is not bound (our case — we use MySQL auto-increment id).
     * Returning false prevents Eloquent's insertAndSetId() from overwriting slug
     * with the MySQL auto-increment integer after every insert.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    public function getTenantKeyName(): string
    {
        return 'slug';
    }

    /**
     * Dispatch tenancy lifecycle events.
     * NOTE: 'created' intentionally does NOT fire TenantCreated.
     * Database provisioning is gated behind admin approval in AdminController::approve().
     * The TenantCreated event is fired manually there with a double-provisioning guard.
     */
    protected $dispatchesEvents = [
        'created' => \Stancl\Tenancy\Events\TenantCreated::class,
        'saved'   => \Stancl\Tenancy\Events\TenantSaved::class,
        'updated' => \Stancl\Tenancy\Events\TenantUpdated::class,
        'deleted' => \Stancl\Tenancy\Events\TenantDeleted::class,
    ];

    /**
     * These are the columns that exist on the organizations table (not in the data JSON column).
     * stancl/tenancy uses this to know which columns to persist directly vs via JSON.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'name',
            'slug',
            'description',
            'cover_photo',
            'gcash_qr_code',
            'status',
            'subscription_paid_at',
            'proof_of_payment',
            'total_payments_made',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'cover_photo',
        'gcash_qr_code',
        'status',
        'disable_reason',
        'subscription_paid_at',
        'proof_of_payment',
        'total_payments_made',
    ];

    protected $casts = [
        'subscription_paid_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * Rooms are stored in the CENTRAL DB on organizations for admin overview.
     * In the tenant DB context, rooms are queried directly without this relation.
     */
    /**
     * Override HasDomains relationship to use 'slug' as the local key.
     * Without this, Laravel would use the model PK (now 'slug') anyway,
     * but being explicit prevents future confusion.
     */
    public function domains(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            DomainModel::class, // App\Models\Domain — has correct tenant() FK
            'tenant_id',
            'slug'
        );
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'organization_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }
}

