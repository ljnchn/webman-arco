<?php

namespace App\Admin\Service;

use App\Admin\Model\Config;

class ConfigService extends BaseService
{
    public function __construct()
    {
        $this->model = new Config();
    }
}