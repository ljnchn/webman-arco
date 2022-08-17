<?php

use App\Admin\Controller\DeptController;
use App\Admin\Controller\MenuController;
use App\Admin\Controller\IndexController;
use App\Admin\Controller\MonitorController;
use App\Admin\Controller\DictTypeController;
use app\Admin\Controller\DictDataController;
use App\Admin\Controller\RoleController;
use Webman\Route;

//Route::get('/api/test/{param}', [\App\Admin\Controller\Test::class, 'index'])->name('test route name');

Route::group('/api/', function () {
    Route::get('captchaImage', [IndexController::class, 'captchaImage']);
    Route::post('login', [IndexController::class, 'login']);
})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
]);

Route::group('/api/', function () {
    Route::get('getInfo', [IndexController::class, 'getInfo']);
    Route::get('getRouters', [IndexController::class, 'getRouters']);
    Route::post('logout', [IndexController::class, 'logout']);
    Route::get('monitor/logininfor/list', [MonitorController::class, 'loginInfo'])->name('monitor:logininfor:query');

    // 部门管理
    Route::get('system/dept/list', [DeptController::class, 'list'])->name('');
    Route::get('system/dept/list/exclude/{id}', [DeptController::class, 'exclude'])->name('');
    Route::get('system/dept/{id}', [DeptController::class, 'info'])->name('');
    Route::post('system/dept', [DeptController::class, 'add'])->name('');
    Route::put('system/dept', [DeptController::class, 'edit'])->name('');
    Route::delete('system/dept/{id}', [DeptController::class, 'del'])->name('');
    // 菜单管理
    Route::get('system/menu/treeselect', [RoleController::class, 'treeSelect'])->name('');
    Route::get('system/menu/roleMenuTreeselect/{id}', [RoleController::class, 'roleMenuTreeselect'])->name('');
    Route::get('system/menu/list', [MenuController::class, 'list'])->name('');
    Route::get('system/menu/{id}', [MenuController::class, 'info'])->name('');
    Route::post('system/menu', [MenuController::class, 'add'])->name('');
    Route::put('system/menu', [MenuController::class, 'edit'])->name('');
    Route::delete('system/menu/{id}', [MenuController::class, 'del'])->name('');
    // 字典类型管理
    Route::get('system/dict/type/optionselect', [DictTypeController::class, 'optionList'])->name('');
    Route::get('system/dict/type/list', [DictTypeController::class, 'list'])->name('');
    Route::get('system/dict/type/{id}', [DictTypeController::class, 'one'])->name('');
    Route::post('system/dict/type', [DictTypeController::class, 'add'])->name('');
    Route::put('system/dict/type', [DictTypeController::class, 'edit'])->name('');
    Route::delete('system/dict/type/{id}', [DictTypeController::class, 'del'])->name('');
    // 字典数据管理
    Route::get('system/dict/data/type/{type}', [DictDataController::class, 'getDictDataByType']);
    Route::get('system/dict/data/list', [DictDataController::class, 'list'])->name('');
    Route::get('system/dict/data/{id}', [DictDataController::class, 'info'])->name('');
    Route::post('system/dict/data', [DictDataController::class, 'add'])->name('');
    Route::put('system/dict/data', [DictDataController::class, 'edit'])->name('');
    Route::delete('system/dict/data/{id}', [DictDataController::class, 'del'])->name('');
    // 角色管理
    Route::get('system/role/list', [RoleController::class, 'list'])->name('');
    Route::get('system/role/{id}', [RoleController::class, 'info'])->name('');
    Route::post('system/role', [RoleController::class, 'add'])->name('');
    Route::put('system/role', [RoleController::class, 'edit'])->name('');
    Route::delete('system/role/{id}', [RoleController::class, 'del'])->name('');
    Route::put('system/role/changeStatus', [RoleController::class, 'changeStatus'])->name('');


})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
    App\Middleware\Auth::class,
    App\Middleware\Pagination::class,
]);