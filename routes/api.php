<?php

use Webman\Route;

Route::get('/api', [\App\Api\Controller\Index::class, 'index']);
Route::group('/api', function () {
    Route::get('/index', [\App\Api\Controller\Index::class, 'index']);
    Route::get('/user/{uid}', [\App\Api\Controller\Index::class, 'user']);
})->middleware([]);