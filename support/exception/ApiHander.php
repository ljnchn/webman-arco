<?php

namespace support\exception;

use App\Enums\ApiCode;
use Webman\Http\Request;
use Webman\Http\Response;
use Throwable;
use Webman\Exception\ExceptionHandler;


class ApiHander extends ExceptionHandler
{
    public $dontReport = [
        BusinessException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render(Request $request, Throwable $exception): Response
    {
        $code = $exception->getCode();

        $json = ['code' => $code ? $code : ApiCode::FAIL, 'msg' => $exception->getMessage()];
        $this->_debug && $json['traces'] = (string)$exception;
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
