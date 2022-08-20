<?php

namespace App\Admin\Service;

use App\Admin\Model\Config;

class ConfigService
{

    use TraitService;

    public function __construct()
    {
        $this->model = new Config();
    }
}