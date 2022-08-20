<?php


namespace App\Admin\Controller;

use App\Admin\Service\MenuService;
use DI\Annotation\Inject;

class MenuController
{
    /**
     * @Inject
     * @var MenuService
     */
    private MenuService $service;

    use TraitController;
}
