<?php
declare(strict_types=1);


use App\Http\Controllers\BankController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', BankController::class)
    ->parameters(['' => 'banks']);

