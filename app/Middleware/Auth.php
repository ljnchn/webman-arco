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
            return failJson('登陆已失效');
        }
        $userInfo = user()->getInfo();
        $routeName = $request->route->getName();
        if ($routeName && !in_array($routeName, $userInfo['permissions'])) {
            return noAccessJson('无权限', $userInfo['permissions']);
        }
        // 请求继续穿越
        return $handler($request);
    }
}
