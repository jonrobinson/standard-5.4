<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Organization;

class VerifyOwnsSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sub_domain = $request->route()->parameter('subdomain');
        $organization = Organization::bySlug($sub_domain)->first();
        if (!$organization) {
            abort(403, 'Unauthorized action.');
        }
        if (!$organization->hasUser(Auth::user())) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
