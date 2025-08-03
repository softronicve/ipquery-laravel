<?php

namespace Softronic\Ipquery;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Softronic\Ipquery\Middleware\IpqueryMiddleware;

class IpqueryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('ipquery', function($app) {
            return new Ipquery();
        });
    }

    public function boot()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(IpqueryMiddleware::class);
    }
}
