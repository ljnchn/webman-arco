<?php

use App\Admin\Controller\Dict;
use App\Admin\Controller\Index;
use App\Admin\Controller\Monitor;
use Webman\Route;

//Route::get('/api/test/{param}', [\App\Admin\Controller\Test::class, 'index'])->name('test route name');

Route::group('/api/', function () {
    Route::get('captchaImage', [Index::class, 'captchaImage']);
    Route::post('login', [Index::class, 'login']);
})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
]);

Route::group('/api/', function () {
    Route::get('getInfo', [Index::class, 'getInfo']);
    Route::get('getRouters', [Index::class, 'getRouters']);
    Route::post('logout', [Index::class, 'logout']);
    Route::get('system/dict/data/type/{type}', [Dict::class, 'getDictDataByType']);

    Route::get('monitor/logininfor/list', [Monitor::class, 'loginInfo'])->name('monitor:logininfor:query');
})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
    App\Middleware\Auth::class,
    App\Middleware\Pagination::class,
]);