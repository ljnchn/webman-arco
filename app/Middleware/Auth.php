<?php

namespace App\Middleware;

use App\Admin\User;
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
        response()->withHeaders([
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin' => $request->header('Origin', '*'),
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => '*',
        ]);
        $authorization = $request->header('Authorization');

        // 判断授权
        if (!$authorization) {
            throw new Exception('no auth token');
        }
        $token = str_replace('Bearer ', '', $authorization);
        // 用户未登录
        if (!$token) {
            return failJson('请先登录');
        }
        // 登录已失效
        if (!user()->isLogin($token)) {
            return failJson('登陆已失效', [$token]);
        }
        // 请求继续穿越
        return $handler($request);
    }
}
