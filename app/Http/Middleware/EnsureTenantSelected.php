<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('tenant_id')) {
            return redirect()->route('tenants.select');
        }

        return $next($request);
    }
}