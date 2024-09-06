<?php
declare(strict_types=1);


use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::controller(TagController::class)->group(function () {
    Route::post('/set-sorting', 'setSorting')->name('set-sorting');
});

Route::apiResource('', TagController::class)->parameters(['' => 'tag']);

