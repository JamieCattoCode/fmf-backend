<?php

namespace App\Providers;

use App\Repository\Eloquent\FurnitureStoreRepository;
use App\Repository\FurnitureStoreInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FurnitureStoreInterface::class, FurnitureStoreRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
