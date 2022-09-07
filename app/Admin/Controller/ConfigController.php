<?php


namespace App\Admin\Controller;

use App\Admin\Service\ConfigService;
use App\Admin\Validate\ConfigValidate;

class ConfigController extends BaseController
{
    public function __construct()
    {
        $this->service     = new ConfigService();
        $this->validate    = new ConfigValidate();
        $this->customParam = ['configName', 'configKey', 'configType'];

        parent::__construct();
    }

}
