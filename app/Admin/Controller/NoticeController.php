<?php


namespace App\Admin\Controller;

use App\Admin\Service\NoticeService;

class NoticeController extends BaseController
{
    public function __construct()
    {
        $this->service     = new NoticeService();
        $this->customParam = ['noticeType', 'createBy'];
        parent::__construct();
        $noticeTitle = request()->get('noticeTitle');
        if ($noticeTitle) {
            $this->where[] = ['notice_title', 'like', '%' . $noticeTitle . '%'];
        }
    }

}
