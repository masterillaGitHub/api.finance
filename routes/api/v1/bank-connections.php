<?php
declare(strict_types=1);


use App\Http\Controllers\BankConnectionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', BankConnectionController::class)
    ->except(['index', 'show', 'update', 'destroy'])
    ->parameters(['' => 'bank-connections']);

