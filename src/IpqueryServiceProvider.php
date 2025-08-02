<?php

namespace Softronic\Ipquery;

use Illuminate\Support\ServiceProvider;

class IpqueryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ipquery', function($app) {
            return new Ipquery();
        });
    }

    public function boot()
    {
        $this->app['router']->aliasMiddleware('ipquery', IpqueryMiddleware::class);
    }
}