<?php

/**
 * Here is your custom functions.
 */

use App\Admin\User;
use App\Enums\HttpCode;
use Illuminate\Support\Str;
use support\Response;

/**
 * @param array    $data
 * @param int|null $httpCode
 * @param int      $options
 * @return Response
 */
function apiJson(array $data, int $httpCode = null, int $options = JSON_UNESCAPED_UNICODE): Response
{
    $httpCode = $httpCode ?? HttpCode::SUCCESS();
    return new Response($httpCode, ['Content-Type' => 'application/json'], json_encode($data, $options));
}

/**
 * 成功返回json
 *
 * @param array    $data
 * @param string   $msg
 * @param int|null $code
 * @return Response
 */
function successJson(array $data = [], string $msg = 'success', int $code = null): Response
{
    $code = $code ?? HttpCode::SUCCESS();
    return apiJson(['code' => $code, 'msg' => $msg, 'data' => $data]);
}

/**
 * 失败返回json
 *
 * @param string   $msg
 * @param array    $data
 * @param int|null $code
 * @return Response
 */
function failJson(string $msg = 'fail', array $data = [], int $code = null): Response
{
    $code = $code ?? HttpCode::FAIL();
    return apiJson(['code' => $code, 'msg' => $msg, 'data' => $data]);
}

/**
 * 失败返回json
 *
 * @param string   $msg
 * @param array    $data
 * @param int|null $code
 * @return Response
 */
function noAccessJson(string $msg = 'no access', array $data = [], int $code = null): Response
{
    $code = $code ?? HttpCode::NO_ACCESS();
    return apiJson(['code' => $code, 'msg' => $msg, 'data' => $data]);
}

/**
 * @return User
 */
function user(): User
{
    if (!request()->user_instance) {
        request()->user_instance = new User();
    }
    return request()->user_instance;
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

/**
 * 返回驼峰格式的属性数组
 * @param $attributes
 * @return array
 */
function getCamelAttributes($attributes): array
{
    $camelAttributes = [];
    foreach ($attributes as $k => $v) {
        $camelAttributes[Str::camel($k)] = $v;
    }
    return $camelAttributes;
}

/**
 * @param array $data
 * @param string $primary
 * @param string $parent
 * @param string $children
 * @return array
 */
function toTree(array $data = [], string $primary = 'id', string $parent = 'parent_id', string $children = 'children'): array
{
    // data is empty
    if (count($data) === 0) {
        return [];
    }

    // parameter missing
    if (!array_key_exists($primary, head($data)) || !array_key_exists($parent, head($data))){
        return [];
    }

    $items = [];
    foreach ($data as $v) {
        $items[@$v[$primary]] = $v;
    }


    $tree = [];
    foreach ($items as $item) {
        if (isset($items[$item[$parent]])) {
            $items[$item[$parent]][$children][] = &$items[$item[$primary]];
        } else {
            $tree[] = &$items[$item[$primary]];
        }
    }

    return $tree;
}
