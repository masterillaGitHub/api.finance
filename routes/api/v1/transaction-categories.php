<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/category-step', 'categoryStepIndex')->name('category-step.index');
Route::post('/set-sorting', 'setSorting')->name('category-step.set-sorting');
