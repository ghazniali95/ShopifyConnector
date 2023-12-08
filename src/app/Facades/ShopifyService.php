<?php

namespace ShopifyConnector\App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * ShopifyService Facade.
 *
 * This facade provides a static interface to the Shopify service.
 * It extends Laravel's Facade base class and defines the method
 * getFacadeAccessor to specify what service container binding it represents.
 *
 * Facades allow for easier and more readable syntax when accessing
 * complex functionality from the Shopify service.
 */
class ShopifyService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This method returns the service container binding type that
     * this facade is responsible for. When this facade is used,
     * it will resolve to the 'shopifyservice' binding in the service container.
     *
     * @return string The name of the binding in the service container.
     */
    protected static function getFacadeAccessor()
    {
        return 'shopifyservice';
    }
}
