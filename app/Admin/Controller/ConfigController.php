<?php


namespace App\Admin\Controller;

use App\Admin\Service\ConfigService;

class ConfigController
{
    private ConfigService $service;
    use TraitController;

    public function __construct()
    {
        $this->service = new ConfigService();
    }

}
