<?php

use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('health-check', [
    'as' => 'health-check',
    'uses' => HealthCheckController::class,
]);
