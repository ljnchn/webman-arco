<?php


namespace App\Admin\Controller;

use App\Admin\Service\UserService;
use support\Request;
use support\Response;

class Index
{

    /**
     * @Inject
     * @var userService
     */
    private UserService $userService;

    public function index(Request $request): Response
    {
        return successJson('用户ID：' . user()->getUid());
    }


    public function login(Request $request): Response
    {
        $email = $request->post('email');
        $password = $request->post('password');

        if (!$email || !$password) {
            return failJson('邮箱或密码不能为空');
        }
        if (!user()->login($email, $password)) {
            return failJson('登陆失败');
        }
        return successJson('登陆成功', ['token' => user()->getToken()]);
    }

    public function logout(Request $request): Response
    {
        user()->logout();
        return successJson('退出登录');
    }

    public function info(Request $request): Response
    {
        $userInfo = $this->userService->getUserInfo();
        return successJson('success', $userInfo);
    }

}
