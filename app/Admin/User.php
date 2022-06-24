<?php

namespace App\Admin;

use support\Redis;

class User
{
    /**
     * 静态成品变量 保存全局实例
     */
    private static ?User $_instance = NULL;
    protected int $uid;
    protected string $token;

    protected function __construct() { }

    protected function __clone() { }

    /**
     * 静态工厂方法，返还此类的唯一实例
     */
    public static function getInstance(): ?User
    {
        if (is_null(self::$_instance)) {
             self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function __callStatic($method, $args)
    {
        $_instance = self::getInstance();

        return $_instance->$method(...$args);
    }

    public  function getUid(): int
    {
        return $this->uid;
    }

    public  function setUid($uid)
    {
        $this->uid = $uid;
    }

    public  function getToken(): string
    {
        return $this->token;
    }

    public  function setToken($token)
    {
        $this->token = $token;
    }

    public  function isLogin($token): bool
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

    public function login($uid): string
    {
        $expired = config('common.auth.expired');
        $token = generateToken($uid . time());
        Redis::set("bearer:" . $token, $uid, 'EX', $expired);
        return $token;
    }

}
