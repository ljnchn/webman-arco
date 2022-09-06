<?php

namespace App\Admin\Service;

use App\Admin\Model\Config;
use App\Admin\Validate\ConfigValidate;

class ConfigService extends BaseService
{
    public function __construct()
    {
        $this->model = new Config();
        $this->validate = new ConfigValidate();
    }
}