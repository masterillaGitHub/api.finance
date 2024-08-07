<?php
declare(strict_types=1);

use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountSumsController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Accounts
Route::prefix('accounts')
    ->name('accounts.')
    ->controller(AccountController::class)
    ->group(base_path('routes/api/v1/accounts.php'));
Route::apiResource('accounts', AccountController::class);


// Account categories
Route::prefix('account-categories')
    ->name('account-categories.')
    ->controller(AccountCategoryController::class)
    ->group(base_path('routes/api/v1/account-categories.php'));
Route::apiResource('account-categories', AccountCategoryController::class)
    ->only(['index']);


// Currencies
Route::apiResource('currencies', CurrencyController::class)
    ->only(['index']);

// Account sums
Route::apiResource('account-sums', AccountSumsController::class)
    ->only(['store', 'update']);

// Transaction categories
Route::apiResource('transaction-categories', TransactionCategoryController::class);

// Transactions
Route::apiResource('transactions', TransactionController::class);
