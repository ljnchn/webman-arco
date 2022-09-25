<?php


namespace App\Admin\Controller;

use App\Admin\Service\PostService;
use App\Admin\Validate\PostValidate;

class PostController extends BaseController
{
    public function __construct()
    {
        $this->service     = new PostService();
        $this->validate    = new PostValidate();
        $this->customParam = ['postCode', 'postName'];

        parent::__construct();
    }

}
