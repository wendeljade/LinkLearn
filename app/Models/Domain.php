<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends BaseDomain
{
    /**
     * Override the tenant() relationship.
     *
     * BaseDomain::tenant() calls belongsTo(Organization::class) which Eloquent
     * derives as FK = 'organization_id' (from the class name). But the actual
     * FK column is 'tenant_id' pointing to organizations.slug.
     *
     * Explicitly specifying both keys fixes the null-tenant issue.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            config('tenancy.tenant_model'), // App\Models\Organization
            'tenant_id',                    // FK on domains table
            'slug'                          // owner key on organizations table
        );
    }
}
