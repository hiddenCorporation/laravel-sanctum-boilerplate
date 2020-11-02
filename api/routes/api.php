<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;

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

/*
authentification
*/
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:3,10');
    Route::post('signup', [AuthController::class, 'register'])->middleware('throttle:2,2');

    Route::group([
      'middleware' => 'auth:sanctum'
    ], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'actualUser']);
    });

});

Route::fallback([AuthController::class, 'notFound'])->name('api.fallback.404');


