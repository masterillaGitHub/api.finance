<?php
declare(strict_types=1);

use App\Http\Controllers\BankAccountController;
use Illuminate\Support\Facades\Route;

Route::controller(BankAccountController::class)->group(function () {
    Route::post('', 'store')->name('store');
    Route::post('update-transactions', 'updateTransactions')->name('update-transactions');
});
