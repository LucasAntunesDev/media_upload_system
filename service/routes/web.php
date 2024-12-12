<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VideoController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/teste', function () {
    return 'roots bloody roots';
});

Route::prefix('videos')->group(function () {
    Route::get('list', [VideoController::class, 'index']);

    Route::get('show/{id}', [VideoController::class, 'show']);

    Route::post('create', [VideoController::class, 'store']);

    Route::delete('{video}', [VideoController::class, 'destroy']);
});

Route::prefix('images')->group(function () {
    Route::get('list', [ImageController::class, 'index']);

    Route::post('create', [ImageController::class, 'store']);

    Route::delete('{image}', [ImageController::class, 'destroy']);
});

Route::prefix('user')->group(function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::get('tokenProfile', [AuthController::class, 'getProfile']);

    Route::get('showUser/{id}', [AuthController::class, 'showUser']);

    Route::middleware([JwtMiddleware::class])->get('logout', [AuthController::class, 'logout']);
});
