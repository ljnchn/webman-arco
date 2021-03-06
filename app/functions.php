<?php

/**
 * Here is your custom functions.
 */

use App\Enums\HttpCode;
use support\Response;

/**
 * @param $data
 * @param int|null $httpCode
 * @param int $options
 * @return Response
 */
function apiJson($data, int $httpCode = null, int $options = JSON_UNESCAPED_UNICODE): Response
{
    $httpCode = $httpCode ?? HttpCode::SUCCESS();
    return new Response($httpCode, ['Content-Type' => 'application/json'], json_encode($data, $options));
}

/**
 * 成功返回json
 *
 * @param array $data
 * @param string $msg
 * @param int|null $code
 * @return Response
 */
function successJson(array $data = [],string $msg = 'success', int $code = null): Response
{
    $code = $code ?? HttpCode::SUCCESS();
    return apiJson(['code' => $code, 'msg' => $msg, 'data' => $data]);
}

/**
 * 失败返回json
 *
 * @param string $msg
 * @param array $data
 * @param int|null $code
 * @return Response
 */
function failJson(string $msg = 'fail', array $data = [], int $code = null): Response
{
    $code = $code ?? HttpCode::FAIL();
    return apiJson(['code' => $code, 'msg' => $msg, 'data' => $data]);
}

function getUserId()
{
    $tenantId = request()->uid ?? false;
    if (!$tenantId) {
        throw new \Exception('user id not null');
    }
    return $tenantId;
}

/**
 * 生成登录时的 token
 *
 * @param String $data
 * @return String
 */
function generateToken(string $data): string
{
    $key = config('common.auth.key');
    $alg = config('common.auth.alg');

    return hash_hmac($alg, $data, $key);
}
