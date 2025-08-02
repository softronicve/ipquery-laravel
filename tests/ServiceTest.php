<?php

namespace Softronic\Ipquery\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Softronic\Ipquery\Ipquery;
use Illuminate\Support\Facades\Cache;

class ServiceTest extends TestCase
{
    public function test_get_details()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'ip' => '190.97.233.197',
                'location' => ['country' => 'Venezuela']
            ])),
        ]);

        $ipquery = new Ipquery();
        $ipquery->client = new \GuzzleHttp\Client(['handler' => HandlerStack::create($mock)]);
        
        $result = $ipquery->getDetails('190.97.233.197');
        
        $this->assertEquals('Venezuela', $result['location']['country']);
        $this->assertEquals('190.97.233.197', $result['ip']);
    }

    public function test_cache_works()
    {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(['ip' => 'cached']);

        $ipquery = new Ipquery();
        $result = $ipquery->getDetails('192.168.1.1');
        
        $this->assertEquals('cached', $result['ip']);
    }
}