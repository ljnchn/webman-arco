<?php

namespace App\Api\Controller;

use support\Request;
use support\Response;

class Index
{
    public function index(Request $request): Response
    {
        return successJson('hello webman start');
    }

    public function user(Request $request, $uid): Response
    {
        return successJson('uid is ' . $uid);
    }

}
