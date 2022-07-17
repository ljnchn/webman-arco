<?php

namespace App\Admin;

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
        $user = Db::table('sys_user')->where('username', $username)->first();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user->password)) {
            return false;
        }
        $uid = $user->user_id;
        $this->afterLogin($uid);
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
        $uid = $user->user_id;
        $this->afterLogin($uid);
        return true;
    }

    public function afterLogin($uid): void
    {
        $expired = config('common.auth.expired');
        $token = generateToken($uid . time());
        Redis::set("bearer:" . $token, $uid, 'EX', $expired);
        $this->setUid($uid);
        $this->setToken($token);
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
