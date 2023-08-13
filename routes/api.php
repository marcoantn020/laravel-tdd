<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/users', \App\Http\Controllers\Api\UserController::class);
//Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
//Route::post('/users', [\App\Http\Controllers\Api\UserController::class, 'store']);
//Route::get('/users/{email}', [\App\Http\Controllers\Api\UserController::class, 'show']);
//Route::put('/users/{email}', [\App\Http\Controllers\Api\UserController::class, 'update']);
//Route::delete('/users/{email}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);
