<?php

namespace App\Admin\Service;

use App\Admin\Model\Menu;

class MenuService {

    use TraitService;

    public function __construct()
    {
        $this->model = new Menu();
    }

}