<?php
namespace App\Middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AccessControl implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
//        $header = [
//            'Access-Control-Allow-Credentials' => 'true',
//            'Access-Control-Allow-Origin' => $request->header('Origin', '*'),
//            'Access-Control-Allow-Methods' => '*',
//            'Access-Control-Allow-Headers' => '*',
//        ];

        return $request->method() == 'OPTIONS' ?  response('') : $handler($request);
    }
}
