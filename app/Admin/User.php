<?php

namespace App\Admin;

use Carbon\Carbon;
use support\Db;
use support\Redis;

class User
{
    protected int $uid = 0;
    protected string $token = '';


    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid($uid): void
    {
        $this->uid = $uid;
    }

    public function isLogin($token): bool
    {
        // 登录已失效
        $uid = Redis::get("bearer:" . $token);
        if (!$uid) {
            return false;
        }
        $this->setUid($uid);
        $this->setToken($token);
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
        $user = Db::table('sys_user')->where('user_name', $username)->first();
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
        $user = Db::table('sys_user')->where('email', $email)->first();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user->password)) {
            return false;
        }
        $this->afterLogin($user);
        return true;
    }

    public function afterLogin($user): void
    {
        $uid = $user->user_id;
        $userName = $user->user_name;

        $expired = config('common.auth.expired');
        $token = generateToken($uid . time());
        Redis::set("bearer:" . $token, $uid, 'EX', $expired);
        $this->setUid($uid);
        $this->setToken($token);
        // 登陆记录
        $ip = Request()->getRealIp();
        Db::table('sys_user')->where('user_id', $uid)->update([
            'login_ip' => $ip,
            'login_date' => Carbon::now(),
        ]);
        // 登陆日志
        $this->loginLog($userName, 0, '登陆成功');
    }

    public function loginLog($userName, $status, $msg = '') {
        // 登陆记录
        $ip = Request()->getRealIp();
        $os = trim(Request()->header('sec-ch-ua-platform'), '"');
        // todo 分析 user-agent
        $browser = 'Edge';
        Db::table('sys_user_login')->insert([
            'user_name' => $userName,
            'ipaddr' => $ip,
            'login_location' => '',
            'browser' => $browser,
            'os' => $os,
            'status' => $status,
            'msg' => $msg,
            'login_time' => Carbon::now(),
        ]);
    }

    public function logout(): int
    {
        $token = $this->getToken();
        return Redis::del("bearer:" . $token);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken($token): void
    {
        $this->token = $token;
    }

}
