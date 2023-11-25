<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantSession
{
    final public function handle(Request $request, Closure $next): mixed
    {
        $subdomain = explode('.', $request->getHost())[0];
        config(['session.cookie' => 'laravel_session_' . $subdomain]);

        return $next($request);
    }
}
