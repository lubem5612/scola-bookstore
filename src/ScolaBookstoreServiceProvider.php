<?php

namespace Transave\ScolaBookstore;

use Illuminate\Support\ServiceProvider;

class ScolaBookstoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'transave');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'transave');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scola-bookstore.php', 'scola-bookstore');

        // Register the service the package provides.
        $this->app->singleton('scola-bookstore', function ($app) {
            return new ScolaBookstore;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['scola-bookstore'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/scola-bookstore.php' => config_path('scola-bookstore.php'),
        ], 'scola-bookstore.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/transave'),
        ], 'scola-bookstore.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/transave'),
        ], 'scola-bookstore.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/transave'),
        ], 'scola-bookstore.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
