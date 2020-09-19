<?php

namespace Domains\Locations;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LocationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        Route::group(['middleware' => 'auth'], fn () => $this->loadRoutesFrom(__DIR__ . '/routes.php'));
    }
}
