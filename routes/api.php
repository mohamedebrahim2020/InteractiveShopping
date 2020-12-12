<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

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
//->withoutMiddleware('auth:sanctum');
Route::post('/login', [ApiAuthController::class,'login'])->name('api.login');
Route::post('/register',[ApiAuthController::class,'register']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/product/store',[ProductController::class, 'store']);
    Route::post('/logout',[ApiAuthController::class,'logout'])->name('api.logout');
    Route::get('/cart/add/{product}',[CartController::class,'addToCart'])->name('add.cart');
    Route::get('/cart/remove/{product}',[CartController::class,'removeFromCart'])->name('remove.cart');
    Route::get('/cart/increase/{product}',[CartController::class,'increaseQuantity'])->name('increase.quantity');
    Route::get('/cart/decrease/{product}',[CartController::class,'decreaseQuantity'])->name('decrease.quantity');
    Route::get('/cart/retrieve/{identifier}',[CartController::class,'getCart']);
    Route::post('/address/add',[AddressController::class,'addAddress']);
    Route::get('/user/addresses/list',[AddressController::class,'getAddresses']);
});
Route::get('/redirect',[ApiAuthController::class,'redirectToProvider']);
Route::get('/callback',[ApiAuthController::class,'handleProviderCallback']);





