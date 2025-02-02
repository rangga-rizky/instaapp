<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    Route::post('/posts', [\App\Http\Controllers\Api\PostController::class, 'create']);
    Route::get('/posts', [\App\Http\Controllers\Api\PostController::class, 'index']);
    Route::get('/posts/{id}', [\App\Http\Controllers\Api\PostController::class, 'show']);

    Route::post('/replies', [\App\Http\Controllers\Api\ReplyController::class, 'create']);

    Route::post('/likes/{type}/{id}', [\App\Http\Controllers\Api\LikeController::class, 'like']);
    Route::delete('/likes/{type}/{id}', [\App\Http\Controllers\Api\LikeController::class, 'unlike']);
});
