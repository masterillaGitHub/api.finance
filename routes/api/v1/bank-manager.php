<?php
declare(strict_types=1);

use App\Http\Controllers\BankManagerController;
use Illuminate\Support\Facades\Route;

Route::controller(BankManagerController::class)->group(function () {
    Route::get('get-accounts', 'getAccounts');
});
