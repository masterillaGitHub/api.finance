<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', fn () => [
    'Laravel' => app()->version(),
    'session.domain' => config('session.domain'),
    'cors.allowed_origins' => config('cors.allowed_origins'),
    'app.url' => config('app.url'),
    'app.frontend_url' => config('app.frontend_url'),
    'sanctum.stateful' => config('sanctum.stateful'),
]);
