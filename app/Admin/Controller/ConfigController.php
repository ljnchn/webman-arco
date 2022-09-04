<?php


namespace App\Admin\Controller;

use App\Admin\Service\ConfigService;

class ConfigController extends BaseController
{
    public function __construct()
    {
        $this->service     = new ConfigService();
        $this->customParam = ['configName', 'configKey', 'configType'];

        parent::__construct();
    }

}
