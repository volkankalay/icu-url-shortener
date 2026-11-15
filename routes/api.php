<?php

use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/token', [TokenController::class, 'store'])->middleware('throttle:6,1')->name('api.token');
Route::post('/links', [LinkController::class, 'store'])->middleware('auth:api')->name('api.links.store');
