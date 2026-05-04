<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the slug from the URL route
    $slug = $request->route('org_slug');
    
    // 2. Find the Organization in the database
    $org = \App\Models\Organization::where('slug', $slug)->first();

    // 3. If Organization does not exist, throw a 404 error
    if (!$org) {
        abort(404, 'Organization not found.');
    }

// 4. THE KILL SWITCH: If status is not active, redirect org members to payment or block access
        if ($org->status !== 'active') {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->id === $org->user_id || $user->organization_id === $org->id) {
                    return redirect()->route('org.subscription.payment', $slug);
                }
            }
        return response()->view('errors.subscription-expired');
    }

    // 5. Share the current organization data with the request
    $request->merge(['current_org' => $org]);

    // 6. Forget the org_slug parameter so it doesn't interfere with controller method arguments
    $request->route()->forgetParameter('org_slug');
    
        return $next($request);
    }
}
