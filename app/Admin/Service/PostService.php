<?php

namespace App\Admin\Service;

use App\Admin\Model\Post;

class PostService
{

    use TraitService;

    public function __construct()
    {
        $this->model = new Post();
    }
}