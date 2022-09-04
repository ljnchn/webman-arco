<?php


namespace App\Admin\Controller;

use App\Admin\Service\UserLoginService;

class UserLoginController extends BaseController
{

    public function __construct()
    {
        $this->service     = new UserLoginService();
        $this->customParam = ['userName', 'ipaddr'];

        parent::__construct();
        if (!$this->descOrder && !$this->ascOrder) {
            $this->descOrder = ['login_time'];
        }
    }
}
