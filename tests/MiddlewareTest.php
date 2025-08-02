<?php

namespace Softronic\Ipquery\Tests;

use Illuminate\Http\Request;
use Softronic\Ipquery\Facades\Ipquery;
use Softronic\Ipquery\Middleware\IpqueryMiddleware;
use Mockery as m;

class MiddlewareTest extends TestCase
{
    public function test_middleware_injects_ipquery_data()
    {
        // Mock de la facade
        Ipquery::shouldReceive('getDetails')
            ->once()
            ->with('192.168.1.1')
            ->andReturn(['ip' => '192.168.1.1']);

        $middleware = new IpqueryMiddleware();
        $request = new Request();
        $request->server->set('REMOTE_ADDR', '192.168.1.1');

        $middleware->handle($request, function ($req) {
            $this->assertEquals('192.168.1.1', $req->ipquery['ip']);
        });
    }
}