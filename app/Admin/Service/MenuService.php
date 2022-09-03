<?php

namespace App\Admin\Service;

use App\Admin\Model\Menu;

class MenuService extends BaseService
{
    public function __construct()
    {
        $this->model = new Menu();
    }

}