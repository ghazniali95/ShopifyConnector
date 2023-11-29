<?php
namespace ShopifyConnector\Tests;

use PHPUnit\Framework\TestCase;
use ShopifyConnector\Services\ShopifyService;

class ShopifyConnectorTest extends TestCase
{
    public function testConnect()
    {
        $shopifyService = new ShopifyService();
        $result = $shopifyService->connect();
        $this->assertEquals('Connected to Shopify', $result);
    }
}
