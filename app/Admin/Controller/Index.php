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

    public function index(): Response
    {
        return successJson('用户ID：' . user()->getUid());
    }


    public function login(Request $request): Response
    {
        $email = $request->post('username');
        $password = $request->post('password');

        if (!$email || !$password) {
            return failJson('邮箱或密码不能为空');
        }
        if (!user()->loginEmail($email, $password)) {
            return failJson('登陆失败');
        }
        return successJson('登陆成功', ['token' => user()->getToken()]);
    }

    public function logout(): Response
    {
        user()->logout();
        return successJson('退出登录');
    }

    public function info(): Response
    {
        $userInfo = $this->userService->getUserInfo();
        return successJson('success', $userInfo);
    }

    public function menu()
    {
        return successJson('success', $this->userService->getUserMenu());
    }

}
