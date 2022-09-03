<?php

namespace App\Admin\Service;

use App\Admin\Model\Post;

class PostService extends BaseService
{

    public function __construct()
    {
        $this->model = new Post();
    }
}