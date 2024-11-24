<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/teste', function () {
    return 'roots bloody roots';
});

Route::prefix('videos')->group(function () {
    Route::get('list', [VideoController::class, 'index']);

    Route::post('create', [VideoController::class, 'store']);

    Route::delete('delete/{video}', [VideoController::class, 'destroy']);
});

Route::prefix('images')->group(function () {
    Route::get('list', [ImageController::class, 'index']);

    Route::post('create', [ImageController::class, 'store']);

    Route::delete('delete/{image}', [ImageController::class, 'destroy']);
});
