<?php

use App\Admin\Controller\Dept;
use App\Admin\Controller\Menu;
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

    // 部门管理
    Route::get('system/dept/list', [Dept::class, 'list'])->name('');
    Route::get('system/dept/list/exclude/{id}', [Dept::class, 'exclude'])->name('');
    Route::get('system/dept/{id}', [Dept::class, 'info'])->name('');
    Route::post('system/dept', [Dept::class, 'add'])->name('');
    Route::put('system/dept', [Dept::class, 'edit'])->name('');
    Route::delete('system/dept/{id}', [Dept::class, 'del'])->name('');
    // 菜单管理
    Route::get('system/menu/list', [Menu::class, 'list'])->name('');
    Route::get('system/menu/{id}', [Menu::class, 'info'])->name('');
    Route::post('system/menu', [Menu::class, 'add'])->name('');
    Route::put('system/menu', [Menu::class, 'edit'])->name('');
    Route::delete('system/menu/{id}', [Menu::class, 'del'])->name('');

})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
    App\Middleware\Auth::class,
    App\Middleware\Pagination::class,
]);