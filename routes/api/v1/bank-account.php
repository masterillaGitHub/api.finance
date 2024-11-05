<?php
declare(strict_types=1);

use App\Http\Controllers\BankAccountController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', BankAccountController::class)
    ->except(['index', 'show', 'update', 'destroy'])
    ->parameters(['' => 'bank-account']);
