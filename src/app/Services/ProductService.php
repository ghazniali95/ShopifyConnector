<?php

namespace ShopifyConnector\App\Services;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

Class ProductService extends ShopifyConnector
{
    public function __construct( string $shopUrl = null , string $version = null , string $accessToken = null )
    {
        parent::__construct($shopUrl , $version , $accessToken);
    }

    /**
     * Get products from Shopify.
     *
     * @return array
     * @throws Exception
     */
    public function getProducts(): array
    {
        $this->throttleApiCall();

        try {
            $response = $this->client->request('GET', 'products.json');
            $body = $response->getBody();
            $data = json_decode($body, true);

            return $data['products'];
        } catch (GuzzleException $e) {
            // Throw a new exception, you can customize the message as needed
            throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
        }
    }
}
