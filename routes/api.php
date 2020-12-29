<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\WorkingDayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestingController;
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
Route::post('/login', [AuthController::class,'login'])->name('api.login');
Route::post('/register', [AuthController::class,'register']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/product', [ProductController::class, 'store']);
    Route::post('/logout', [AuthController::class,'logout'])->name('api.logout');
    Route::prefix('cart')->group(function () {
        Route::post('products/{product}', [CartController::class,'addToCart'])->name('add.cart');
        Route::delete('products/{product}', [CartController::class,'removeFromCart'])->name('remove.cart');
        Route::put('products/{product}/increment', [CartController::class,'increaseQuantity'])->name('inc.quantity');
        Route::put('products/{product}/decrement', [CartController::class,'decreaseQuantity'])->name('dec.quantity');
    });
 
    Route::apiResource('addresses', AddressController::class)->only(['index', 'store']);
    Route::prefix('order')->group(function () {
        Route::get('/cancel/reasons', [OrderController::class,'listCancellationReasons']);
        Route::post('/{order}/cancel', [OrderController::class,'cancel']);
        Route::get('/rates/{rate}/tags', [ReviewController::class,'getRateTags']);
        Route::post('/{order}/review', [ReviewController::class,'review']);
    });
    Route::apiResource('orders', OrderController::class)->only(['index', 'store']);
    Route::group(['prefix' => 'admin',  'middleware' => 'role:Admin'], function () {
        Route::apiResource('workdays', WorkingDayController::class)->only(['index' , 'store']);
    });
    // Route::prefix('admin')->group(['middleware' => 'role:admin'], function () {
    //     Route::apiResource('workdays', WorkingDayController::class)->only(['index' , 'store']);
    // });
});
Route::get('/redirect', [AuthController::class,'redirectToProvider']);
Route::get('/callback', [AuthController::class,'handleProviderCallback']);
Route::get('/index/{order}', [TestingController::class, 'testing']);
