<?php


namespace App\Admin\Controller;

use App\Admin\Service\NoticeService;

class NoticeController extends BaseController
{
    public function __construct()
    {
        $this->service = new NoticeService();
    }

}
