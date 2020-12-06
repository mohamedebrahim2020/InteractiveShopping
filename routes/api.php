<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/product/store',[ProductController::class, 'store']);
Route::post('/login', [ApiAuthController::class,'login'])->name('api.login');
Route::post('/register',[ApiAuthController::class,'register']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout',[ApiAuthController::class,'logout'])->name('api.logout');
});
Route::get('/redirect',[ApiAuthController::class,'redirectToProvider']);
Route::get('/callback',[ApiAuthController::class,'handleProviderCallback']);

