<?php
declare(strict_types=1);


use App\Http\Controllers\TransactionTagController;
use Illuminate\Support\Facades\Route;

Route::controller(TransactionTagController::class)->group(function () {
    Route::post('/set-sorting', 'setSorting')->name('set-sorting');
});

Route::apiResource('', TransactionTagController::class)->parameters(['' => 'tag']);

