<?php

namespace App\Admin\Service;

use App\Admin\Model\Notice;

class NoticeService
{

    use TraitService;

    public function __construct()
    {
        $this->model = new Notice();
    }
}