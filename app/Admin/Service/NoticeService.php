<?php

namespace App\Admin\Service;

use App\Admin\Model\Notice;

class NoticeService extends ParentService
{

    use TraitService;

    public function __construct()
    {
        $this->model = new Notice();
    }
}