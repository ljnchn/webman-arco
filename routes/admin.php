<?php

use Webman\Route;

Route::get('/admin/test', [\App\Admin\Controller\Dict::class, 'index']);
Route::get('/admin/captchaImage', [\App\Admin\Controller\Index::class, 'captchaImage']);
Route::post('/admin/login', [\App\Admin\Controller\Index::class, 'login']);

Route::group('/admin/', function () {
    Route::get('getInfo', [\App\Admin\Controller\Index::class, 'getInfo']);
    Route::get('getRouters', [\App\Admin\Controller\Index::class, 'getRouters']);
    Route::post('logout', [\App\Admin\Controller\Index::class, 'logout']);
    Route::get('dict/data/type/{type}', [\App\Admin\Controller\Dict::class, 'getDictDataByType']);
    Route::get('monitor/logininfor/list', [\App\Admin\Controller\Monitor::class, 'loginInfo']);
})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\Auth::class,
]);