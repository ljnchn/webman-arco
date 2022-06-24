<?php

use Webman\Route;

Route::group('/api/user', function () {
    Route::get('login', [\App\Api\Controller\Index::class, 'index']);
    Route::get('logout', [\App\Api\Controller\Index::class, 'user']);
})->middleware([]);