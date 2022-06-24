<?php

namespace App\Middleware;

use Exception;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * Class Auth
 * @package app\middleware
 */
class Auth implements MiddlewareInterface
{
    /**
     * @throws Exception
     */
    public function process(Request $request, callable $handler): Response
    {
        // 处理跨域
        $response = $request->method() == 'OPTIONS' ? response('') : $handler($request);
        $response->withHeaders([
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin' => $request->header('Origin', '*'),
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => '*',
        ]);
        // 判断授权
        if (!$request->header('Authorization')) {
            throw new Exception('no auth token');
        }
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        // 用户未登录
        if (!$token) {
            return failJson('请先登录');
        }
        // 登录已失效
        if (!User()->isLogin($token)) {
            return failJson('登陆已失效', [$token]);
        }
        // 请求继续穿越
        return $handler($request);
    }
}
