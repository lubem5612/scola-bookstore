<?php

namespace Transave\ScolaBookstore;

use Illuminate\Support\ServiceProvider;


class PaystackServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/paystack.php', 'paystack');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/paystack.php' => config_path('paystack.php'),
        ], 'config');
    }
}
