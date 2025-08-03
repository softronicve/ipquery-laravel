<?php

namespace Softronic\Ipquery\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Softronic\Ipquery\Ipquery;

class ServiceTest extends TestCase
{
    public function test_get_details_with_valid_ipv4()
    {
        Http::fake([
            'https://api.ipquery.io/190.97.233.197' => Http::response([
                'ip' => '190.97.233.197',
                'location' => ['country' => 'Venezuela'],
                'isp' => ['asn' => 'AS12345']
            ])
        ]);

        $ipquery = new Ipquery();
        $result = $ipquery->getDetails('190.97.233.197');
        
        $this->assertEquals('Venezuela', $result['location']['country']);
        $this->assertEquals('AS12345', $result['isp']['asn']);
        $this->assertEquals('190.97.233.197', $result['ip']);
    }

    public function test_get_details_with_valid_ipv6()
    {
        $ipv6 = '2a09:bac5:d3a9:aa::11:1dc';
        
        Http::fake([
            "https://api.ipquery.io/$ipv6" => Http::response([
                'ip' => $ipv6,
                'location' => ['city' => 'Barquisimeto'],
                'risk' => ['is_vpn' => false]
            ])
        ]);

        $ipquery = new Ipquery();
        $result = $ipquery->getDetails($ipv6);
        
        $this->assertEquals('Barquisimeto', $result['location']['city']);
        $this->assertFalse($result['risk']['is_vpn']);
    }

    public function test_uses_request_ip_when_no_ip_provided()
    {
        // Configurar IP en el request
        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.0.100']);
        $this->app->instance('request', $request);

        Http::fake([
            'https://api.ipquery.io/192.168.0.100' => Http::response([
                'ip' => '192.168.0.100',
                'location' => ['country' => 'Testland']
            ])
        ]);

        $ipquery = new Ipquery();
        $result = $ipquery->getDetails();
        
        $this->assertEquals('192.168.0.100', $result['ip']);
        $this->assertEquals('Testland', $result['location']['country']);
    }

    public function test_cache_works_correctly()
    {
        $ip = '203.0.113.5';
        $cachedData = ['ip' => $ip, 'location' => ['city' => 'Cached City']];
        
        Cache::shouldReceive('remember')
            ->once()
            ->with("ipquery_$ip", \Mockery::any(), \Mockery::any())
            ->andReturn($cachedData);

        $ipquery = new Ipquery();
        $result = $ipquery->getDetails($ip);
        
        $this->assertEquals('Cached City', $result['location']['city']);
    }

    public function test_handles_api_error_gracefully()
    {
        $ip = '192.0.2.1';
        
        Http::fake([
            "https://api.ipquery.io/$ip" => Http::response('Server Error', 500)
        ]);

        $ipquery = new Ipquery();
        $result = $ipquery->getDetails($ip);
        
        $this->assertEmpty($result);
    }

    public function test_returns_empty_array_for_invalid_ip()
    {
        $invalidIp = 'invalid-ip-address';
        
        $ipquery = new Ipquery();
        $result = $ipquery->getDetails($invalidIp);
        
        $this->assertEmpty($result);
    }

    public function test_actual_api_integration_with_known_ip()
    {
        $testIp = '8.8.8.8'; // Google DNS
        $ipquery = new Ipquery();
        $result = $ipquery->getDetails($testIp);
        
        $this->assertIsArray($result);
        $this->assertEquals($testIp, $result['ip'] ?? null);
        $this->assertArrayHasKey('location', $result);
        $this->assertArrayHasKey('country', $result['location']);
        $this->assertArrayHasKey('isp', $result);
    }
}