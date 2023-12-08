<?php

namespace ShopifyConnector\App\Providers;

use Illuminate\Support\ServiceProvider;
use ShopifyConnector\App\Services\ShopifyConnector;
use ShopifyConnector\App\Console\Commands\InstallShopifyConnector;

/**
 * Shopify Connector Service Provider.
 *
 * This service provider is responsible for bootstrapping the ShopifyConnector
 * package, including merging configurations, registering commands, and
 * publishing assets. It also binds the ShopifyConnector service into the
 * Laravel service container.
 */
class ShopifyConnectorProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered
     * by the framework.
     *
     * @return void
     */
    public function boot()
    {
        // Merges the package's configuration file with the application's published copy.
        // This will allow users to override package configuration in their own config files.
        $this->mergeConfigFrom(__DIR__ . '/../../config/shopifyconnector.php', 'shopifyconnector');

        if ($this->app->runningInConsole()) {
            // Register the custom Artisan command for the package.
            $this->commands([
                InstallShopifyConnector::class,
            ]);

            // Publishes the package's configuration file to the application's config directory.
            // This makes it possible to modify the package's configuration from the application.
            $this->publishes([
                __DIR__ . '/../../config/shopifyconnector.php' => config_path('shopifyconnector.php'),
            ], 'shopifyconnector');
        }
    }

    /**
     * Register bindings in the service container.
     *
     * This method is called by Laravel to register services in the service container.
     * It binds the 'shopifyservice' key in the container to an instance of ShopifyConnector.
     *
     * @return void
     */
    public function register()
    {
        // Bind the 'shopifyservice' key to a closure that returns an instance of ShopifyConnector.
        // This allows the app to resolve 'shopifyservice' from the container anywhere in the application.
        $this->app->bind('shopifyservice', function ($app) {
            return new ShopifyConnector(config('shopifyconnector'));
        });
    }
}
