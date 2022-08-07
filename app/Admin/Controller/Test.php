<?php


namespace App\Admin\Controller;

use support\Request;
use support\Response;

class Test
{

    public function index(Request $request, $param): Response
    {
        return successJson([
            'method' => $request->method(),
            'url'    => $request->url(),
            'path'   => $request->path(),
            'param'  => $param,
            'route'  => $request->route->getPath(),
            'name'   => $request->route->getName(),
        ]);
    }

}
