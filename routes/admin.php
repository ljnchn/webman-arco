<?php

use Webman\Route;

Route::get('/admin/captchaImage', [\App\Admin\Controller\Index::class, 'captchaImage']);
Route::post('/admin/login', [\App\Admin\Controller\Index::class, 'login']);

Route::group('/admin/', function () {
    Route::post('getInfo', [\App\Admin\Controller\Index::class, 'getInfo']);
    Route::post('getRouters', [\App\Admin\Controller\Index::class, 'getRouters']);
    Route::post('logout', [\App\Admin\Controller\Index::class, 'logout']);
})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\Auth::class,
]);