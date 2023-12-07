<?php

namespace ShopifyConnector\App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ShopifyConnector
{
    protected Client $client;
    protected string $accessToken;
    protected string $shopUrl;
    protected string $version;

    private array $lastApiCallTimes = [];
    /**
     * Create a new ShopifyService instance.
     *
     * @param string $accessToken
     * @param string $shopUrl
     */
    public function __construct(string $accessToken = null , string $shopUrl = null , string $version = null)
    {
        $this->accessToken = config('shopifyconnector.access_token' , $accessToken);

        $this->shopUrl = config('shopifyconnector.shop_url' , $shopUrl);

        $this->version = config('shopifyconnector.api_version' , $version);

        $this->client = new Client([
            'base_uri' => "https://".$this->shopUrl."/admin/api/".$this->version."/",
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);
    }

    /**
     * Throttles the API calls to comply with Shopify's rate limit.
     */
    private function throttleApiCall(): void
    {
        $currentTime = microtime(true);
        $this->lastApiCallTimes[] = $currentTime;

        if (count($this->lastApiCallTimes) > 2) {
            $timeDifference = $currentTime - $this->lastApiCallTimes[0];

            if ($timeDifference < 1) {
                // Delay the execution to maintain the rate limit
                usleep((1 - $timeDifference) * 1000000);
            }

            array_shift($this->lastApiCallTimes);
        }
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
