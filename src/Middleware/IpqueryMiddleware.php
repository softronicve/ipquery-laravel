<?php

namespace Softronic\Ipquery\Middleware;

use Closure;
use Softronic\Ipquery\Facades\Ipquery;

class IpqueryMiddleware
{
    public function handle($request, Closure $next)
    {
        $request->ipquery = Ipquery::getDetails($request->ip());
        return $next($request);
    }
}