<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\productsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});
Route::post('/signup', [AuthController::class, 'signUp']);
Route::post('/signin', [AuthController::class, 'signIn']);
Route::prefix('admin')->group(function () {
    Route::post('/signup', [AdminAuthController::class, 'signUp']);
    Route::post('/signin', [AdminAuthController::class, 'signIn']);
});

Route::middleware(['auth:api', 'adminMiddleware:api'])
    ->group(function () {
        Route::post('/categories', [categoriesController::class, 'create']);
        Route::post('/products', [productsController::class, 'create']);
    });

Route::get('/categories', [categoriesController::class, 'get']);
