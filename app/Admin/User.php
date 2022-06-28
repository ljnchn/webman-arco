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

    public function login($email, $password): bool
    {
        $user = Db::table('sys_user')->where('email', $email)->first();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user->password)) {
            return false;
        }
        $uid = $user->user_id;
        $expired = config('common.auth.expired');
        $token = generateToken($uid . time());
        Redis::set("bearer:" . $token, $uid, 'EX', $expired);
        $this->setUid($uid);
        $this->setToken($token);
        return true;
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
