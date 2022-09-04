<?php


namespace App\Admin\Controller;

use App\Admin\Service\PostService;

class PostController extends BaseController
{
    public function __construct()
    {
        $this->service     = new PostService();
        $this->customParam = ['postCode', 'postName'];

        parent::__construct();
    }

}
