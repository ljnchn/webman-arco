<?php

namespace App\Admin\Service;

use App\Admin\Model\UserLogin;

class UserLoginService extends BaseService
{
    public function __construct()
    {
        $this->model = new UserLogin();
    }
}