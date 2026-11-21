<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminManagement\Http\Controllers\DashboardController;
use Modules\AdminManagement\Http\Controllers\PaymentController;
use Modules\AdminManagement\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| AdminManagement Module Routes
|--------------------------------------------------------------------------
*/

// Web Page Views
Route::get('/', [DashboardController::class, 'renderView'])->name('dashboard');
Route::get('/payments', [PaymentController::class, 'renderView'])->name('payments');
Route::get('/expenses', [ExpenseController::class, 'renderView'])->name('expenses');

// Payments API
Route::prefix('api/payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('api.payments.index');
    Route::post('/', [PaymentController::class, 'store'])->name('api.payments.store');
    Route::get('/{id}', [PaymentController::class, 'show'])->name('api.payments.show');
    Route::post('/{id}/update', [PaymentController::class, 'update'])->name('api.payments.update');
    Route::post('/{id}/delete', [PaymentController::class, 'destroy'])->name('api.payments.destroy');
});

// Expenses API
Route::prefix('api/expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('api.expenses.index');
    Route::post('/', [ExpenseController::class, 'store'])->name('api.expenses.store');
    Route::get('/{id}', [ExpenseController::class, 'show'])->name('api.expenses.show');
    Route::post('/{id}/update', [ExpenseController::class, 'update'])->name('api.expenses.update');
    Route::post('/{id}/delete', [ExpenseController::class, 'destroy'])->name('api.expenses.destroy');
});
