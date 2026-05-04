<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByPathException;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyByOrgSlug extends InitializeTenancyByPath
{
    public function getTenant(Request $request): string
    {
        $slug = $request->route('tenant');

        if (!$slug) {
            throw new TenantCouldNotBeIdentifiedByPathException($request);
        }

        // Find tenant by slug so path-based tenancy works correctly.
        $tenant = \App\Models\Organization::where('slug', $slug)->first();

        if (!$tenant) {
            throw new TenantCouldNotBeIdentifiedByPathException($request);
        }

        return $tenant->getTenantKey();
    }
}