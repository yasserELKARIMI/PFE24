<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Options\Bexio;
use App\Options\Settings;
use App\integration\WooCommerceBexioOrderIntegration;
use App\integration\WooCommerceBexioProductIntegration;
// use App\integration\WooCommerceBexioContactIntegration;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Bexio::class, function () {
            return new Bexio();
        });
        $this->app->singleton(Settings::class, function () {
            return Settings::getInstance();
        });
        $this->app->singleton(WooCommerceBexioIntegration::class, function ($app) {
            return new WooCommerceBexioProductIntegration();
        });
        $this->app->singleton(WooCommerceBexioIntegration::class, function ($app) {
            return new WooCommerceBexioOrderIntegration();
        });
        // $this->app->singleton(WooCommerceBexioIntegration::class, function ($app) {
        //     return new WooCommerceBexioContactIntegration();
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(Bexio::class);
        $this->app->make(Settings::class);
        $this->app->make(WooCommerceBexioOrderIntegration::class);
        $this->app->make(WooCommerceBexioProductIntegration::class);
        // $this->app->make(WooCommerceBexioContactIntegration::class);



    }
}
