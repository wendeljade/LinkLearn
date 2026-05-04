<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SetTenantUrlDefault
{
    public function handle(Request $request, Closure $next)
    {
        if (function_exists('tenant') && tenant()) {
            URL::defaults(['tenant' => tenant('slug')]);
            if ($request->route()) {
                $request->route()->forgetParameter('tenant');
            }
        }
        return $next($request);
    }
}
