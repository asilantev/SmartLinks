<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{slug}', [\App\Http\Controllers\RedirectController::class, 'redirect'])
    ->middleware([
        Middleware\Response_405_MethodNotAllowed_Middleware::class,
        Middleware\Response_404_NotFound_Middleware::class,
        Middleware\Response_422_UprocessableContent_Middleware::class,
        Middleware\Response_307_TemporaryRedirect_Middleware::class
    ]);
