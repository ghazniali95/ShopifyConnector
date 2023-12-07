<?php

namespace ShopifyConnector\App\Providers;

use Illuminate\Support\ServiceProvider;
use ShopifyConnector\App\Services\ShopifyConnector;
use ShopifyConnector\App\Console\Commands\InstallShopifyConnector;

class ShopifyConnectorProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/shopifyconnector.php', 'shopifyconnector');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallShopifyConnector::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../config/shopifyconnector.php' => config_path('shopifyconnector.php'),
            ], 'shopifyconnector');
        }

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ShopifyService', "ShopifyConnector\\App\\Facades\\ShopifyService");
    }

    public function register()
    {
        $this->app->bind('shopifyservice', function ($app) {
            return new ShopifyConnector();
        });
    }
}
