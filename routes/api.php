<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
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
//->withoutMiddleware('auth:sanctum');
Route::post('/login', [ApiAuthController::class,'login'])->name('api.login');
Route::post('/register', [ApiAuthController::class,'register']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/product', [ProductController::class, 'store']);
    Route::post('/logout', [ApiAuthController::class,'logout'])->name('api.logout');
    Route::prefix('cart')->group(function () {
        Route::post('add/{product}', [CartController::class,'addToCart'])->name('add.cart');
        Route::delete('remove/{product}', [CartController::class,'removeFromCart'])->name('remove.cart');
        Route::put('product/{product}/increment', [CartController::class,'increaseQuantity'])->name('inc.quantity');
        Route::put('product/{product}/decrement', [CartController::class,'decreaseQuantity'])->name('dec.quantity');
    });
 
    Route::apiResource('addresses', AddressController::class)->only(['index', 'store']);
    Route::prefix('order')->group(function () {
        Route::post('/', [OrderController::class,'submitOrder']);
        Route::get('/cancel/reasons', [OrderController::class,'listCancellationReasons']);
        Route::post('/cancel', [OrderController::class,'cancelOrder']);
        Route::get('/rates/{rate}/tags', [ReviewController::class,'getRateTags']);
        Route::post('/{order}/review', [ReviewController::class,'reviewOrder']);
    });

});
Route::get('/redirect', [ApiAuthController::class,'redirectToProvider']);
Route::get('/callback', [ApiAuthController::class,'handleProviderCallback']);
