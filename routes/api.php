<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::get('/', [Api\StatsController::class, 'root']);
    Route::post('/login', [Api\AuthController::class, 'login'])->middleware('throttle:login');

    // Publik
    Route::get('/articles', [Api\ArticleController::class, 'index']);
    Route::get('/articles/{id}', [Api\ArticleController::class, 'show'])->whereNumber('id');
    Route::get('/categories', [Api\CategoryController::class, 'index']);
    Route::get('/featured', [Api\FeaturedController::class, 'show']);
    Route::get('/stats', [Api\StatsController::class, 'index']);

    // Perlu token Bearer (admin atau operator)
    Route::middleware('api.auth')->get('/me', [Api\AuthController::class, 'me']);

    Route::middleware('api.auth:staff')->group(function () {
        Route::post('/articles', [Api\ArticleController::class, 'store']);
        Route::match(['put', 'patch'], '/articles/{id}', [Api\ArticleController::class, 'update'])->whereNumber('id');
        Route::delete('/articles/{id}', [Api\ArticleController::class, 'destroy'])->whereNumber('id');

        Route::post('/categories', [Api\CategoryController::class, 'store']);
        Route::match(['put', 'patch'], '/categories/{id}', [Api\CategoryController::class, 'update'])->whereNumber('id');
        Route::delete('/categories/{id}', [Api\CategoryController::class, 'destroy'])->whereNumber('id');

        Route::match(['put', 'patch'], '/featured', [Api\FeaturedController::class, 'update']);
    });

    // Khusus admin
    Route::middleware('api.auth:admin')->group(function () {
        Route::get('/users', [Api\UserController::class, 'index']);
        Route::post('/users', [Api\UserController::class, 'store']);
        Route::match(['put', 'patch'], '/users/{id}', [Api\UserController::class, 'update'])->whereNumber('id');
        Route::delete('/users/{id}', [Api\UserController::class, 'destroy'])->whereNumber('id');
    });
});
