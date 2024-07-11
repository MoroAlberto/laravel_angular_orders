<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('orders', OrderController::class);
Route::apiResource('products', ProductController::class);
Route::post('/orders/{order}/products/{product}', [OrderProductController::class, 'attachProductToOrder']);

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/
