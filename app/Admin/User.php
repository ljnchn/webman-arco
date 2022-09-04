<?php

namespace App\Admin;

use App\Admin\Model\UserLogin;
use App\Admin\Service\UserService;
use App\Enums\UserStatus;
use Carbon\Carbon;
use Exception;
use Jenssegers\Agent\Agent;
use support\Redis;

class User
{
    protected int $uid = 0;
    protected array $info = [];
    protected string $token = '';

    /**
     * 验证登录 token 是否有效
     * @param $token
     * @return bool
     * @throws Exception
     */
    public function isLogin($token): bool
    {
        // 登录已失效
        $uid = Redis::get("bearer:" . $token);
        if (!$uid) {
            return false;
        }
        $userService = new UserService();
        $info        = $userService->getUserInfo($uid);
        $this->setUid($uid);
        $this->setToken($token);
        $this->setInfo($info);
        return true;
    }

    /**
     * 通过用户名，密码登录
     * @param $username
     * @param $password
     * @return bool
     */
    public function loginUsername($username, $password): bool
    {
        $user = Model\User::query()->where([
            'user_name' => $username,
            'status'    => UserStatus::NORMAL()
        ])->first();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user->password)) {
            return false;
        }
        $this->afterLogin($user);
        return true;
    }

    /**
     * 通过邮箱，密码登录
     * @param $email
     * @param $password
     * @return bool
     */
    public function loginEmail($email, $password): bool
    {
        $user = Model\User::query()->where('email', $email)->first();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user->password)) {
            return false;
        }
        $this->afterLogin($user);
        return true;
    }

    /**
     * 登录后处理
     * @param $user
     * @return void
     */
    public function afterLogin($user): void
    {
        $uid      = $user->user_id;
        $userName = $user->user_name;
        $expired  = config('common.auth.expired');
        $token    = generateToken($uid . time());
        Redis::set("bearer:" . $token, $uid, 'EX', $expired);
        $this->setUid($uid);
        $this->setToken($token);
        // 登陆记录
        $ip = Request()->getRealIp();
        Model\User::query()->where('user_id', $uid)->update([
            'login_ip'   => $ip,
            'login_date' => Carbon::now(),
        ]);
        // 登陆日志
        $this->loginLog($userName, 0, '登陆成功');
    }

    /**
     * 记录登录日志
     * @param string $userName
     * @param string $status
     * @param string $msg
     * @return void
     */
    public function loginLog(string $userName, string $status, string $msg = ''): void
    {
        // 登陆记录
        $ip       = Request()->getRealIp();
        $location = '';
        $agent    = new Agent();
        $agent->setUserAgent(Request()->header('user-agent'));
        $agent->setHttpHeaders(Request()->header());
        if ($ip) {
            $location = ip2region($ip);
        }
        UserLogin::insert([
            'user_name'      => $userName,
            'ipaddr'         => $ip,
            'login_location' => $location,
            'browser'        => $agent->browser(),
            'os'             => $agent->platform(),
            'status'         => $status,
            'msg'            => $msg,
            'login_time'     => Carbon::now(),
        ]);
    }

    /**
     * 退出登陆
     * @return int
     */
    public function logout(): int
    {
        $token = $this->getToken();
        return Redis::del("bearer:" . $token);
    }

    public function isAdmin(): int
    {
        return (bool)$this->info['user']['admin'];
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid($uid): void
    {
        $this->uid = $uid;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    public function setInfo($info): void
    {
        $this->info = $info;
    }

    public function getName() {
        return $this->getInfo()['user']['userName'];
    }

    public function getPermissions()
    {
        return $this->getInfo()['permissions'];
    }

}
