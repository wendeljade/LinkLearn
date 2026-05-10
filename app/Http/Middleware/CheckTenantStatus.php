<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        // Don't block logout route
        if ($request->routeIs('org.logout')) {
            return $next($request);
        }

        $org = tenant();

        if ($org && $org->status !== 'active') {
            if ($org->disable_reason === 'issue') {
                return response()->view('errors.org-disabled', ['org' => $org]);
            }

            if (auth()->check()) {
                $user = auth()->user();
                if ($user->id === $org->user_id || $user->organization_id === $org->id) {
                    $centralDomains = config('tenancy.central_domains', ['localhost']);
                    $centralDomain  = $centralDomains[0];
                    $port = request()->getPort();
                    $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
                    $paymentUrl = request()->getScheme() . '://' . $centralDomain . $portStr . '/org/' . $org->slug . '/subscription/payment';
                    return redirect($paymentUrl);
                }
            }
            return response()->view('errors.subscription-expired', ['org' => $org]);
        }

        return $next($request);
    }
}
