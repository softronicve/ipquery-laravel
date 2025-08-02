<?php

namespace Softronic\Ipquery;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class Ipquery
{
    public $client;
    protected $baseUrl = 'https://api.ipquery.io/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 3.0,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'Ipquery-Laravel/1.0'
            ]
        ]);
    }

    public function getDetails(string $ip = null): array
    {
        $targetIp = $ip ?: request()->ip();
        $cacheKey = "ipquery_{$targetIp}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($targetIp) {
            try {
                $response = $this->client->get($targetIp);
                $data = json_decode($response->getBody(), true);
                
                return $this->formatResponse($data);
            } catch (RequestException $e) {
                return [
                    'error' => 'IPQuery API error: ' . $e->getMessage(),
                    'success' => false,
                    'ip' => $targetIp
                ];
            }
        });
    }

    protected function formatResponse(array $data): array
    {
        return [
            'success' => true,
            'ip' => $data['ip'] ?? null,
            'isp' => $data['isp'] ?? null,
            'location' => $data['location'] ?? null,
            'risk' => $data['risk'] ?? null,
        ];
    }
}