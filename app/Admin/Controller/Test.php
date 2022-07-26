<?php


namespace App\Admin\Controller;

use support\Request;
use support\Response;

class Test
{

    public function index(Request $request): Response
    {
        return successJson([
            $request->header('sec-ch-ua-platform'),
        ]);
    }

}
