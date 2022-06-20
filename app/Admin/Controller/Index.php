<?php
namespace App\Admin\Controller;

use support\Request;
use support\Response;

class Index
{
    public function index(Request $request): Response
    {
        return successJson('this is admin');
    }

}