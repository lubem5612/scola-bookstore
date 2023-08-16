<?php

namespace Transave\ScolaBookstore;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Transave\ScolaBookstore\Http\Models\User;
use Transave\ScolaCbt\Http\Middlewares\AllowIfAdmin;
use Transave\ScolaCbt\Http\Middlewares\AllowIfPublisher;
use Transave\ScolaCbt\Http\Middlewares\AllowIfSuperAdmin;
use Transave\ScolaCbt\Http\Middlewares\AllowIfUser;
use Transave\ScolaCbt\Http\Middlewares\VerifiedAccount;


class ScolaBookstoreServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */

    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'transave');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bookstore');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->registerRoutes();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Config::set('auth.defaults', [
            'guard' => 'api',
            'passwords' => 'users',
        ]);

        Config::set('auth.guards.api', [
            'driver' => 'session',
            'provider' => 'users',
            'hash' => false,
        ]);

        Config::set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);


        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('superAdmin', AllowIfSuperAdmin::class);
        $router->aliasMiddleware('admin', AllowIfAdmin::class);
        $router->aliasMiddleware('verify', VerifiedAccount::class);
        $router->aliasMiddleware('publisher', AllowIfPublisher::class);
        $router->aliasMiddleware('user', AllowIfUser::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scola-bookstore.php', 'scola-bookstore');
         $this->mergeConfigFrom(__DIR__.'/../config/endpoints.php', 'endpoints');

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
        ], 'bookstore-config');

        // Publishing migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'bookstore-migrations');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bookstore'),
        ], 'views');

    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('scola-bookstore.route.prefix'),
            'middleware' => config('scola-bookstore.route.middleware'),
        ];
    }




}