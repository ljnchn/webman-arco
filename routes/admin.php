<?php

use Webman\Route;

Route::get('/admin/user/test', [\App\Admin\Controller\Test::class, 'index']);
Route::post('/admin/user/login', [\App\Admin\Controller\Index::class, 'login']);

Route::group('/admin/user/', function () {
//    Route::post('index', [\App\Admin\Controller\Index::class, 'index']);
    Route::post('info', [\App\Admin\Controller\Index::class, 'info']);
    Route::post('logout', [\App\Admin\Controller\Index::class, 'logout']);
})->middleware([
    App\Middleware\Auth::class,
]);