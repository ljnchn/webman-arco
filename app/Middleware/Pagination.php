<?php
namespace App\Middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class Pagination implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        $request->pageNum = $request->get('pageNum', 1);
        $request->pageSize = $request->get('pageSize', 10);
        return $handler($request);
    }
    
}
