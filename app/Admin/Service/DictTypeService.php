<?php

namespace App\Admin\Service;

use App\Admin\Model\DictType;

class DictTypeService extends BaseService
{
    public function __construct()
    {
        $this->model = new DictType;
    }
}