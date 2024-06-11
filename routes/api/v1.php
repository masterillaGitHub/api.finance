<?php
declare(strict_types=1);

use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'accounts' => AccountController::class,
]);

Route::apiResource('account-categories', AccountCategoryController::class)
    ->only(['index']);
