<?php

namespace Softronic\Ipquery;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class Ipquery
{
    protected $baseUrl = 'https://api.ipquery.io/';

    public function __construct()
    {

    }

    public function getDetails(string $ip = null): array
    {
        $targetIp = $ip ?: request()->ip();

        if (!filter_var($targetIp, FILTER_VALIDATE_IP)) {
            return [];
        }

        $cacheKey = "ipquery_{$targetIp}";

        $url = $this->baseUrl.$targetIp;

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($url) {
            try {
                $response = Http::timeout(3)->get($url);

                if($response->successful()){
                    $data = $response->json();
                    return $data;
                }
                
                return [];
            } catch (Exception $e) {
                return [
                    'error' => 'IPQuery API error: ' . $e->getMessage(),
                    'success' => false,
                ];
            }
        });
    }
}
