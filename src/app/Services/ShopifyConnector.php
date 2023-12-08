<?php

namespace ShopifyConnector\App\Services;
 
use GuzzleHttp\Client;  
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
    public function __construct( string $shopUrl = null , string $version = null , string $accessToken = null )
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
    protected function throttleApiCall(): void
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


}
