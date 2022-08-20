<?php


namespace App\Admin\Controller;

use App\Admin\Service\NoticeService;

class NoticeController
{
    private NoticeService $service;
    use TraitController;

    public function __construct()
    {
        $this->service = new NoticeService();
    }

}
