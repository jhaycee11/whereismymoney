<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/history', [DashboardController::class, 'history'])->name('dashboard.history');
    
    // Expense routes (Resource controller)
    Route::get('/dashboard/expenses', [ExpenseController::class, 'index'])->name('dashboard.expenses');
    Route::post('/dashboard/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/dashboard/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::put('/dashboard/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/dashboard/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    
    // Income routes (Resource controller)
    Route::get('/dashboard/income', [IncomeController::class, 'index'])->name('dashboard.income');
    Route::post('/dashboard/income', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/dashboard/income/{income}', [IncomeController::class, 'show'])->name('income.show');
    Route::put('/dashboard/income/{income}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/dashboard/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy');
    
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
