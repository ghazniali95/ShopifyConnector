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

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallShopifyConnector::class,
            ]);
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
