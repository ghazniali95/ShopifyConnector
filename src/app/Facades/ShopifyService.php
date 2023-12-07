<?php

namespace ShopifyConnector\App\Facades;

use Illuminate\Support\Facades\Facade;

class ShopifyService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shopifyservice';
    }
}
