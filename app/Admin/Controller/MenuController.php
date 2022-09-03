<?php


namespace App\Admin\Controller;

use App\Admin\Service\MenuService;

class MenuController extends BaseController
{

    public function __construct()
    {
        $this->service = new MenuService();
    }
}
