<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\VideoController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/teste', function () {
    return 'roots bloody roots';
});

Route::prefix('videos')->group(function () {
    Route::get('list', [VideoController::class, 'index']);

    Route::post('create', [VideoController::class, 'store']);

    Route::delete('{video}', [VideoController::class, 'destroy']);
});

Route::prefix('images')->group(function () {
    Route::get('list', [ImageController::class, 'index']);

    Route::post('create', [ImageController::class, 'store']);

    Route::delete('{image}', [ImageController::class, 'destroy']);
});

Route::prefix('user')->group(function () {
    Route::post('register', [JWTAuthController::class, 'register']);

    Route::post('login', [JWTAuthController::class, 'login']);

    Route::middleware([JwtMiddleware::class])->get('logout', [JWTAuthController::class, 'logout']);
});
