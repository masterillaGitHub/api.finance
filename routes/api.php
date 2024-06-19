<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/auth/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])
    ->prefix('v1')
    ->name('v1.')
    ->group(base_path('routes/api/v1.php'));
