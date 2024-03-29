<?php


namespace App\Admin\Controller;

use App\Admin\Service\UserService;
use App\Enums\CacheType;
use App\Enums\HttpCode;
use DI\Annotation\Inject;
use Exception;
use Illuminate\Support\Str;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;
use support\Redis;
use support\Request;
use support\Response;

class IndexController
{

    /**
     * @Inject
     * @var userService
     */
    private UserService $userService;


    public function captchaImage(): Response
    {
        // 初始化验证码类
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder       = new CaptchaBuilder(null, $phraseBuilder);
        // 生成验证码
        $builder->build(100);
        // 将验证码的值存储到 redis 中
        $uuid = Str::uuid();
        Redis::setEx(CacheType::CAPTCHA() . $uuid, 300, strtolower($builder->getPhrase()));
        // 获得验证码图片二进制数据
        $imgContent = base64_encode($builder->get());
        return json([
            'code' => HttpCode::SUCCESS(),
            'img'  => $imgContent,
            'uuid' => $uuid,
            'msg'  => 'success'
        ]);
    }

    public function login(Request $request): Response
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $code     = $request->post('code');
        $uuid     = $request->post('uuid');

        // 验证码
        if (!$code || !$uuid) {
            return failJson('验证码不能为空');
        }
        if (!$username || !$password) {
            return failJson('邮箱或密码不能为空');
        }
        $redisCode = Redis::get(CacheType::CAPTCHA() . $uuid);
        if (!$redisCode) {
            return $this->failLogin($username, '验证码已失效');
        }
        if ($redisCode != $code) {
            return $this->failLogin($username, '验证码错误');
        }
        if (!user()->loginUsername($username, $password)) {
            return $this->failLogin($username, '登陆失败');
        }
        return json([
            'code'  => HttpCode::SUCCESS(),
            'token' => user()->getToken(),
            'msg'   => '登陆成功'
        ]);
    }

    public function failLogin($username, $msg): Response
    {
        user()->loginLog($username, 1, $msg);
        return failJson($msg);
    }

    public function logout(): Response
    {
        user()->logout();
        return successJson();
    }

    /**
     * @throws Exception
     */
    public function getInfo(): Response
    {
        $userInfo = $this->userService->getUserInfo(user()->getUid());
        return json([
            'code'        => HttpCode::SUCCESS(),
            'msg'         => 'success',
            'permissions' => $userInfo['permissions'],
            'roles'       => $userInfo['roles'],
            'user'        => $userInfo['user'],
        ]);
    }

    public function getRouters(): Response
    {
        return successJson($this->userService->getRouters(user()->getUid(), user()->isAdmin()));
    }

}
