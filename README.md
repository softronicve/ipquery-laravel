# Unofficial IpQuery wrapper for Laravel

With this wrapper you can get information from an IP FREE

### Getting Started

You don't need an API KEY or any other configuration, it's very easy 

#### Installation
```bash
composer require softronicve/ipquery-laravel
```

Open your application's `\app\Http\Kernel.php` file and add the following to the `Kernel::middleware` property:

```php
protected $middleware = [
    ...
    \Softronic\Ipquery\Middleware\IpqueryMiddleware::class,
];
```

### Laravel 12 Configuration

Open your application's `\bootstrap\providers.php` file and add the following to your array:
```php
return [
    ...
    \Softronic\Ipquery\IpqueryServiceProvider::class,
];
```

#### Quick Start 

```php
Route::get('/testIpquery', function (Request $request) {
    return $request->ipquery;
});
```

will return the following json with your IP requested:

```php
>>> $request->ipquery
{
  "ip": "1.1.1.1",
  "isp": {
    "asn": "AS13335",
    "org": "Cloudflare, Inc.",
    "isp": "Cloudflare, Inc."
  },
  "location": {
    "country": "Australia",
    "country_code": "AU",
    "city": "Sydney",
    "state": "New South Wales",
    "zipcode": "1001",
    "latitude": -33.8545484001867,
    "longitude": 151.200162009128,
    "timezone": "Australia/Sydney",
    "localtime": "2025-08-03T13:38:55"
  },
  "risk": {
    "is_mobile": false,
    "is_vpn": false,
    "is_tor": false,
    "is_proxy": false,
    "is_datacenter": true,
    "risk_score": 0
  }
}
```