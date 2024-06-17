<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\BillController;
use App\Http\Controllers\Transaction\PaymentMethodController;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => BillController::class, "prefix" => "bill", "as" => "bill."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::get('{id}/checkout', 'checkout')->name('checkout');

        Route::get('/payment_method/get', [PaymentMethodController::class, 'search'])->name('get.payment_method');
    });
});
