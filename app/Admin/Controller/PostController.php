<?php


namespace App\Admin\Controller;

use App\Admin\Service\PostService;

class PostController
{
    private PostService $service;
    use TraitController;

    public function __construct()
    {
        $this->service = new PostService();
    }

}
