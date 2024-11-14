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
Route::prefix('transaction-categories')
    ->name('transaction-categories.')
    ->controller(TransactionCategoryController::class)
    ->group(base_path('routes/api/v1/transaction-categories.php'));
Route::apiResource('transaction-categories', TransactionCategoryController::class);

// Tags
Route::prefix('transaction-tags')
    ->name('transaction-tags.')
    ->group(base_path('routes/api/v1/transaction-tags.php'));

// Transactions
Route::prefix('transactions')
    ->name('transactions.')
    ->group(base_path('routes/api/v1/transactions.php'));

// Banks
Route::prefix('banks')
    ->name('banks.')
    ->group(base_path('routes/api/v1/banks.php'));

// Bank connections
Route::prefix('bank-connections')
    ->name('bank-connections.')
    ->group(base_path('routes/api/v1/bank-connections.php'));

// Bank manager
Route::prefix('bank-manager')
    ->name('bank-manager.')
    ->group(base_path('routes/api/v1/bank-manager.php'));

// Bank account
Route::prefix('bank-accounts')
    ->name('bank-accounts.')
    ->group(base_path('routes/api/v1/bank-accounts.php'));
