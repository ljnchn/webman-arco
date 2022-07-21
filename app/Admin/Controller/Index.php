<?php


namespace App\Admin\Controller;

use App\Admin\Service\UserService;
use support\Redis;
use support\Request;
use support\Response;
use Illuminate\Support\Str;
use Ljnchn\Captcha\CaptchaBuilder;

class Index
{

    /**
     * @Inject
     * @var userService
     */
    private UserService $userService;


    public function captchaImage(): Response
    {
        // 初始化验证码类
        $builder = new CaptchaBuilder;
        // 生成验证码
        $builder->build(100);
        // 将验证码的值存储到 redis 中
        $uuid = Str::uuid();
        Redis::setEx('captcha:' . $uuid,300, strtolower($builder->getPhrase()));
        // 获得验证码图片二进制数据
        $imgContent = base64_encode($builder->get());
        return json([
            'code' => 200,
            'img' => $imgContent,
            'uuid' => $uuid,
            'msg' => 'success'
        ]);
    }


    public function login(Request $request): Response
    {
        $email = $request->post('username');
        $password = $request->post('password');
        $code = $request->post('code');
        $uuid = $request->post('uuid');

        // 验证码
        if (!$code || !$uuid) {
            return failJson('验证码不能为空');
        }
        $redisCode = Redis::get('captcha:' . $uuid);
        if (!$redisCode) {
            return failJson('验证码已失效');
        }
        if ($redisCode != $code) {
            return failJson('验证码错误');
        }
        if (!$email || !$password) {
            return failJson('邮箱或密码不能为空');
        }
        if (!user()->loginEmail($email, $password)) {
            return failJson('登陆失败');
        }
        return successJson(['token' => user()->getToken()], '登陆成功');
    }

    public function logout(): Response
    {
        user()->logout();
        return successJson();
    }

    public function getInfo(): Response
    {
        $userInfo = $this->userService->getUserInfo();
        return successJson($userInfo);
    }

    public function getRouters()
    {
        return successJson($this->userService->getUserMenu());
    }

}
