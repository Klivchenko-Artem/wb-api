<?php

use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\StockController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/stocks', [StockController::class, 'index']);
    Route::get('/incomes', [IncomeController::class, 'index']);
});
