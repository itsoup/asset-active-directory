<?php

use Domains\Locations\Http\Controllers\LocationsStoreController;
use Illuminate\Support\Facades\Route;

Route::post('/locations', LocationsStoreController::class);
