<?php

namespace App\Middleware;

use support\Redis;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class Throttle implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $response = $handler($request);
        $config = config('common.throttle');
        if ($config && $config['enable']) {
            $limit    = $config['limit'];
            $second   = $config['second'];
            $ip       = $request->getRealIp();
            $key      = 'throttle:' . $ip;
            $attempts = Redis::get($key) ?? 0;
            if (!$attempts) {
                Redis::setEx($key, $second, 0);
            }
            if ($attempts >= $limit) {
                return $response->withStatus(429)->withHeaders([
                    'X-Rate-Limit-Limit'     => $limit,
                    'X-Rate-Limit-Remaining' => $limit - $attempts - 1,
                ]);
            }
            Redis::incr($key);
            $response->withHeaders([
                'X-Rate-Limit-Limit'     => $limit,
                'X-Rate-Limit-Remaining' => $limit - $attempts - 1,
            ]);
        }
        // 请求继续穿越
        return $response;
    }
}
