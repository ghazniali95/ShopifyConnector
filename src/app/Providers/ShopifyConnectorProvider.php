<?php

namespace ShopifyConnector\App\Providers; 

use Illuminate\Support\ServiceProvider;

class ShopifyConnectorProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(ShopifyConnectorProvider::class);

    }

    public function register()
    {

    }
}
