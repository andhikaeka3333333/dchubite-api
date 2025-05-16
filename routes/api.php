<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitsReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/forgot-password', [AuthController::class, 'requestOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class);
    Route::put('/products/{id}/activate', [ProductController::class, 'activate']);

    Route::apiResource('categories', CategoryController::class);


    // Route::post('/orders', [OrderController::class, 'createOrder']);
    // Route::get('/orders', [OrderController::class, 'getTransactions']);
    // Route::patch('/orders/{id}', [OrderController::class, 'updateOrderStatus']);
    // Route::get('/orders/today', [OrderController::class, 'getTodayTransactions']);


    Route::prefix('orders')->group(function () {
        Route::post('/order', [OrderController::class, 'createOrderWithPayment']);
        Route::put('/order/{orderId}/success', [OrderController::class, 'markOrderAsSuccess']);
        Route::get('/', [OrderController::class, 'getTransactions']);
        Route::get('/today', [OrderController::class, 'getTodayTransactions']);
    });

    Route::get('/reports/daily', [ProfitsReportController::class, 'generateDailyReport']);
    Route::get('/reports/category', [ProfitsReportController::class, 'getCategoryReport']);
    Route::get('/reports/category-by-date', [ProfitsReportController::class, 'getCategoryReportbyDate']);
    Route::get('/reports/weekly', [ProfitsReportController::class, 'getWeeklyReport']);
    Route::get('/reports/monthly', [ProfitsReportController::class, 'getMonthlyReport']);
    Route::get('/reports/all', [ProfitsReportController::class, 'getAllReport']);
    Route::get('/reports', [ProfitsReportController::class, 'getReport']);

    Route::get('/reports/sold-products', [OrderController::class, 'getSoldProductsToday']);
    Route::get('/reports/sold-by-category', [OrderController::class, 'getSoldProductsByCategoryToday']);
    Route::get('/reports/sold-category-by-date', [OrderController::class, 'getSoldProductsByCategoryByDate']);
});
