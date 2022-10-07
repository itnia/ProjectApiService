<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;

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
Route::middleware('throttle:10,1')->group(function () {
    Route::post('delivery/cost-calculation', [DeliveryController::class, 'calculateDeliveryCost'])->name('delivery.calc');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.list');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('order.details');
    Route::post('orders', [OrderController::class, 'store'])->name('order.create');
    
    //Привязка курьера к заказу
    Route::post('orders/{order}/courier', [OrderController::class, 'changeCourier'])->name('order.courier.change');
});
