<?php

namespace support\exception;

use App\Enums\HttpCode;
use Webman\Http\Request;
use Webman\Http\Response;
use Throwable;
use Webman\Exception\ExceptionHandler;


class AdminHandle extends ExceptionHandler
{

    public function render(Request $request, Throwable $exception): Response
    {
        $code = $exception->getCode();

        $json = ['code' => $code ?: HttpCode::FAIL(), 'msg' => $exception->getMessage()];
        $this->_debug && $json['traces'] = (string)$exception;
        return new Response(
            HttpCode::SUCCESS(),
            ['Content-Type' => 'application/json'],
            json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
