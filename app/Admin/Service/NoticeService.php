<?php

namespace App\Admin\Service;

use App\Admin\Model\Notice;

class NoticeService extends BaseService
{
    public function __construct()
    {
        $this->model = new Notice();
    }
}