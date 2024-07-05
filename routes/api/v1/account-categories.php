<?php
declare(strict_types=1);


use Illuminate\Support\Facades\Route;
Route::get('/main-page', 'mainPage')->name('main-page');
Route::get('/transaction-page', 'transactionPage')->name('transaction-page');
