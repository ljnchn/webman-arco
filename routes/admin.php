<?php

use App\Admin\Controller\Dept;
use App\Admin\Controller\Menu;
use App\Admin\Controller\Index;
use App\Admin\Controller\Monitor;
use App\Admin\Controller\DictType;
use app\Admin\Controller\DictData;
use App\Admin\Controller\Role;
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
    Route::get('monitor/logininfor/list', [Monitor::class, 'loginInfo'])->name('monitor:logininfor:query');

    // 部门管理
    Route::get('system/dept/list', [Dept::class, 'list'])->name('');
    Route::get('system/dept/list/exclude/{id}', [Dept::class, 'exclude'])->name('');
    Route::get('system/dept/{id}', [Dept::class, 'info'])->name('');
    Route::post('system/dept', [Dept::class, 'add'])->name('');
    Route::put('system/dept', [Dept::class, 'edit'])->name('');
    Route::delete('system/dept/{id}', [Dept::class, 'del'])->name('');
    // 菜单管理
    Route::get('system/menu/treeselect', [Role::class, 'treeSelect'])->name('');
    Route::get('system/menu/roleMenuTreeselect/{id}', [Role::class, 'roleMenuTreeselect'])->name('');
    Route::get('system/menu/list', [Menu::class, 'list'])->name('');
    Route::get('system/menu/{id}', [Menu::class, 'info'])->name('');
    Route::post('system/menu', [Menu::class, 'add'])->name('');
    Route::put('system/menu', [Menu::class, 'edit'])->name('');
    Route::delete('system/menu/{id}', [Menu::class, 'del'])->name('');
    // 字典类型管理
    Route::get('system/dict/type/optionselect', [DictType::class, 'optionList'])->name('');
    Route::get('system/dict/type/list', [DictType::class, 'list'])->name('');
    Route::get('system/dict/type/{id}', [DictType::class, 'info'])->name('');
    Route::post('system/dict/type/', [DictType::class, 'add'])->name('');
    Route::put('system/dict/type/', [DictType::class, 'edit'])->name('');
    Route::delete('system/dict/type/{id}', [DictType::class, 'del'])->name('');
    // 字典数据管理
    Route::get('system/dict/data/type/{type}', [DictData::class, 'getDictDataByType']);
    Route::get('system/dict/data/list', [DictData::class, 'list'])->name('');
    Route::get('system/dict/data/{id}', [DictData::class, 'info'])->name('');
    Route::post('system/dict/data', [DictData::class, 'add'])->name('');
    Route::put('system/dict/data', [DictData::class, 'edit'])->name('');
    Route::delete('system/dict/data/{id}', [DictData::class, 'del'])->name('');
    // 角色管理
    Route::get('system/role/list', [Role::class, 'list'])->name('');
    Route::get('system/role/{id}', [Role::class, 'info'])->name('');
    Route::post('system/role', [Role::class, 'add'])->name('');
    Route::put('system/role', [Role::class, 'edit'])->name('');
    Route::delete('system/role/{id}', [Role::class, 'del'])->name('');
    Route::put('system/role/changeStatus', [Role::class, 'changeStatus'])->name('');


})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
    App\Middleware\Auth::class,
    App\Middleware\Pagination::class,
]);