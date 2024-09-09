<?php
declare(strict_types=1);


use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', TransactionController::class)
    ->parameters(['' => 'transaction']);

