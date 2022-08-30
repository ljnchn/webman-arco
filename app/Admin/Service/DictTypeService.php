<?php

namespace App\Admin\Service;

use App\Admin\Model\DictType;

class DictTypeService extends ParentService
{

    use TraitService;

    public function __construct()
    {
        $this->model = new DictType;
    }
}