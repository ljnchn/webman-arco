<?php

namespace App\Middleware;

use Exception;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;
use support\Redis;

/**
 * Class Auth
 * @package app\middleware
 */
class Auth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if (!$request->header('Authorization')) {
            throw new Exception('no auth token');
        }
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        // 用户未登录
        if (!$token) {
            return failJson('请先登录');
        }
        $tid = $request->tid;
        // 登录已失效
        $uid = Redis::get("bearer:$tid:" . $token);
        if (!$uid) {
            return failJson('登陆已失效');
        }
        $request->token = $token;
        $request->uid = $uid;
        // 请求继续穿越
        return $handler($request);
    }
}
