<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnforceLocalhost
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->getHost() === '127.0.0.1') {
            $port = $request->getPort();
            $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
            return redirect($request->getScheme() . '://localhost' . $portStr . $request->getRequestUri());
        }

        return $next($request);
    }
}
