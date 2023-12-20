<?php
namespace  Ghazniali95\ShopifyConnectorTests;

use PHPUnit\Framework\TestCase;
use Ghazniali95\ShopifyConnector\Services\ShopifyService;

class ShopifyConnectorTest extends TestCase
{
    public function testConnect()
    {
        $shopifyService = new ShopifyService();
        $result = $shopifyService->connect();
        $this->assertEquals('Connected to Shopify', $result);
    }
}
