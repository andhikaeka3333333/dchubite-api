<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitsReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);


    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::get('/orders', [OrderController::class, 'getTransactions']);
    Route::patch('/orders/{id}', [OrderController::class, 'updateOrderStatus']);
    Route::get('/orders/today', [OrderController::class, 'getTodayTransactions']);
    Route::get('/reports/daily', [ProfitsReportController::class, 'generateDailyReport']);
    Route::get('/reports/category', [ProfitsReportController::class, 'getCategoryReport']);
    Route::get('/reports', [ProfitsReportController::class, 'getReport']);
});
