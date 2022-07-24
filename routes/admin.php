<?php

use Webman\Route;

Route::get('/admin/captchaImage', [\App\Admin\Controller\Index::class, 'captchaImage']);
Route::post('/admin/login', [\App\Admin\Controller\Index::class, 'login']);

Route::group('/admin/', function () {
    Route::get('getInfo', [\App\Admin\Controller\Index::class, 'getInfo']);
    Route::get('getRouters', [\App\Admin\Controller\Index::class, 'getRouters']);
    Route::post('logout', [\App\Admin\Controller\Index::class, 'logout']);
})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\Auth::class,
]);