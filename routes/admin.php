<?php
use Webman\Route;

Route::get('/admin', [\App\Admin\Controller\Index::class, 'index']);
Route::group('/admin', function () {
     Route::get('/index', [App\Admin\Controller\Index::class, 'index']);
 });