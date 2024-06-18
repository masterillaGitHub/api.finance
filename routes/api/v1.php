<?php
declare(strict_types=1);

use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountSumsController;
use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'accounts' => AccountController::class,
]);

Route::apiResource('account-categories', AccountCategoryController::class)
    ->only(['index']);
Route::get('account-categories/main-page', [AccountCategoryController::class, 'mainPage']);

Route::apiResource('currencies', CurrencyController::class)
    ->only(['index']);

Route::apiResource('account-sums', AccountSumsController::class)
    ->only(['store', 'update']);
