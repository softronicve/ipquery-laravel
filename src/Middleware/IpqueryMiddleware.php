<?php

namespace Softronic\Ipquery\Middleware;

use Closure;
use Softronic\Ipquery\Facades\Ipquery;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class IpqueryMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $ipqueryData = Ipquery::getDetails($request->ip());
        $request->merge(['ipquery' => $ipqueryData]);

        return $next($request);
    }
}
