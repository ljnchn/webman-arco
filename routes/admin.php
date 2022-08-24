<?php

use App\Admin\Controller\ConfigController;
use App\Admin\Controller\DeptController;
use App\Admin\Controller\MenuController;
use App\Admin\Controller\IndexController;
use App\Admin\Controller\MonitorController;
use App\Admin\Controller\DictTypeController;
use app\Admin\Controller\DictDataController;
use App\Admin\Controller\NoticeController;
use App\Admin\Controller\PostController;
use App\Admin\Controller\RoleController;
use App\Admin\Controller\UserController;
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
    Route::get('monitor/logininfor/list', [MonitorController::class, 'loginInfo'])->name('monitor:logininfor:list');

    // 用户管理
    Route::get('system/user/list', [UserController::class, 'list'])->name('system:user:list');
    Route::get('system/user/', [UserController::class, 'info'])->name('system:user:query');
    Route::get('system/user/{id}', [UserController::class, 'one'])->name('system:user:query');
    Route::post('system/user', [UserController::class, 'add'])->name('system:user:add');
    Route::put('system/user', [UserController::class, 'edit'])->name('system:user:edit');
    Route::put('system/user/resetPwd', [UserController::class, 'resetPwd'])->name('system:user:edit');
    Route::delete('system/user/{id}', [UserController::class, 'del'])->name('system:user:remove');
    // 部门管理
    Route::get('system/dept/treeselect', [DeptController::class, 'treeselect'])->name('system:dept:query');
    Route::get('system/dept/list', [DeptController::class, 'allList'])->name('system:dept:list');
    Route::get('system/dept/list/exclude/{id}', [DeptController::class, 'exclude'])->name('system:dept:query');
    Route::get('system/dept/{id}', [DeptController::class, 'one'])->name('system:dept:query');
    Route::post('system/dept', [DeptController::class, 'add'])->name('system:dept:add');
    Route::put('system/dept', [DeptController::class, 'edit'])->name('system:dept:edit');
    Route::delete('system/dept/{id}', [DeptController::class, 'del'])->name('system:dept:remove');
    // 菜单管理
    Route::get('system/menu/treeselect', [RoleController::class, 'treeSelect'])->name('system:menu:query');
    Route::get('system/menu/roleMenuTreeselect/{id}', [RoleController::class, 'roleMenuTreeselect'])->name('system:menu:query');
    Route::get('system/menu/list', [MenuController::class, 'allList'])->name('system:menu:list');
    Route::get('system/menu/{id}', [MenuController::class, 'one'])->name('system:menu:query');
    Route::post('system/menu', [MenuController::class, 'add'])->name('system:menu:add');
    Route::put('system/menu', [MenuController::class, 'edit'])->name('system:menu:edit');
    Route::delete('system/menu/{id}', [MenuController::class, 'del'])->name('system:menu:remove');
    // 字典类型管理
    Route::get('system/dict/type/optionselect', [DictTypeController::class, 'optionList'])->name('system:dict:query');
    Route::get('system/dict/type/list', [DictTypeController::class, 'list'])->name('system:dict:list');
    Route::get('system/dict/type/{id}', [DictTypeController::class, 'one'])->name('system:dict:query');
    Route::post('system/dict/type', [DictTypeController::class, 'add'])->name('system:dict:add');
    Route::put('system/dict/type', [DictTypeController::class, 'edit'])->name('system:dict:edit');
    Route::delete('system/dict/type/{id}', [DictTypeController::class, 'del'])->name('system:dict:remove');
    // 字典数据管理
    Route::get('system/dict/data/type/{type}', [DictDataController::class, 'getDictDataByType']);
    Route::get('system/dict/data/list', [DictDataController::class, 'list'])->name('system:dict:list');
    Route::get('system/dict/data/{id}', [DictDataController::class, 'one'])->name('system:dict:query');
    Route::post('system/dict/data', [DictDataController::class, 'add'])->name('system:dict:add');
    Route::put('system/dict/data', [DictDataController::class, 'edit'])->name('system:dict:edit');
    Route::delete('system/dict/data/{id}', [DictDataController::class, 'del'])->name('system:dict:remove');
    // 角色管理
    Route::get('system/role/list', [RoleController::class, 'list'])->name('system:role:list');
    Route::get('system/role/{id}', [RoleController::class, 'one'])->name('system:role:query');
    Route::post('system/role', [RoleController::class, 'add'])->name('system:role:add');
    Route::put('system/role', [RoleController::class, 'edit'])->name('system:role:edit');
    Route::delete('system/role/{id}', [RoleController::class, 'del'])->name('system:role:remove');
    Route::put('system/role/changeStatus', [RoleController::class, 'changeStatus'])->name('system:role:edit');
    // 岗位管理
    Route::get('system/post/list', [PostController::class, 'list'])->name('');
    Route::get('system/post/{id}', [PostController::class, 'one'])->name('');
    Route::post('system/post', [PostController::class, 'add'])->name('');
    Route::put('system/post', [PostController::class, 'edit'])->name('');
    Route::delete('system/post/{id}', [PostController::class, 'del'])->name('');
    // 参数管理
    Route::get('system/config/list', [ConfigController::class, 'list'])->name('system:config:list');
    Route::get('system/config/{id}', [ConfigController::class, 'one'])->name('system:config:query');
    Route::post('system/config', [ConfigController::class, 'add'])->name('system:config:add');
    Route::put('system/config', [ConfigController::class, 'edit'])->name('system:config:edit');
    Route::delete('system/config/{id}', [ConfigController::class, 'del'])->name('system:config:remove');
    // 通知公告
    Route::get('system/notice/list', [NoticeController::class, 'list'])->name('system:notice:list');
    Route::get('system/notice/{id}', [NoticeController::class, 'one'])->name('system:notice:query');
    Route::post('system/notice', [NoticeController::class, 'add'])->name('system:notice:add');
    Route::put('system/notice', [NoticeController::class, 'edit'])->name('system:notice:edit');
    Route::delete('system/notice/{id}', [NoticeController::class, 'del'])->name('system:notice:remove');


})->middleware([
    App\Middleware\AccessControl::class,
    App\Middleware\TraceLog::class,
    App\Middleware\Auth::class,
    App\Middleware\Pagination::class,
]);