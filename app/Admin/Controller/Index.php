<?php


namespace App\Admin\Controller;

use support\Request;
use support\Response;

class Index
{
    public function index(Request $request): Response
    {
        return successJson('用户ID：' . user()->getUid());
    }

    public function login(Request $request): Response
    {
        $username = $request->post('username');
        $password = $request->post('password');

        if (!$username || !$password) {
            return failJson('用户名或密码不能为空');
        }
//        $user = SysUser::query()->where('user_name', $username)->first();
//        if (!$user) {
//            return failJson('用户不存在');
//        }
//        if (!password_verify($password, $user->password)) {
//            return failJson('密码不匹配');
//        }
        $uid = 110;
        $token = user()->login($uid);
        return successJson('登陆成功', ['token' => $token]);
    }

    public function logout(Request $request)
    {
//        user()->logout();
    }

}
