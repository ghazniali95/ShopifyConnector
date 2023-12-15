<?php

namespace ShopifyConnector\App\Services;

use Exception;
use Shopify\Utils;
use Shopify\Context;
use GuzzleHttp\Client;
use Shopify\Auth\FileSessionStorage;
use App\Models\Channel\ShopifyChannel;

/**
 * Handles the connection and communication with the Shopify API.
 * Manages authentication, request formatting, and rate limiting for API calls.
 */
trait ShopifyConnector
{
    protected Client $client; // HTTP client for making requests to Shopify API.
    protected array $params = []; // Parameters for the request body.
    protected array $options = ['timeout' => 180]; // Parameters for the request body.
    private array $lastApiCallTimes = []; // Timestamps of the last API calls for rate limiting.
    public $orgainzation_id;
    public $channel_id;
    public $session;

    /**
     * Constructor: Sets up the ShopifyConnector with configuration data.
     * Initializes the HTTP client for API requests.
     *
     * @param array $data Configuration data (API key, access token, shop URL, and API version).
     * @throws Exception if required configuration data is missing.
     */
    public function __construct(protected array $data)
    {
        // Assign default configuration if specific data not provided
        // $this->data["api_key"] = ($data["api_key"] ?? config('shopifyconnector.api_key'));
        // $this->data["access_token"] = ($data["access_token"] ?? config('shopifyconnector.access_token'));
        // $this->data["shop_url"] = ($data["shop_url"] ?? config('shopifyconnector.shop_url'));
        // $this->data["api_version"] = ($data["api_version"] ?? config('shopifyconnector.api_version'));

        // // Validate essential data
        // if (empty($this->data["api_key"]) || empty($this->data["access_token"]) || empty($this->data["api_version"]) || empty($this->data["shop_url"])) {
        //     throw new Exception("shop url, api version, access token and api key must be provided");
        // }
        // $this->initializeSDK();
        // $this->initializeClient();
    }


    /**
     * Initializes the HTTP client for Shopify API requests.
     * Configures base URI and headers including authentication details.
     */
    public function initializeClient(): void
    {
        $url = "https://{$this->data["api_key"]}:{$this->data["access_token"]}@{$this->data["shop_url"]}/admin/api/{$this->data["api_version"]}/";

        $this->client = new Client([
            'base_uri' => $url,
            'headers' => ['Content-Type' => 'application/json'],
        ]);
    }

    public function initializeSDK(): void
    {
        Context::initialize(
            env("SHOPIFY_API_KEY","3bda7be4f9789a1f43d84e3d8f13fc22"),
            $this->channelDetail()->access_token,
            env("SHOPIFY_SCOPES","write_products,read_inventory,write_inventory,read_locations"),
            $this->channelDetail()->shop,
            new FileSessionStorage(),
        );

        $requestHeaders = [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => 'SAME_TOKEN_AS_IN_CURL_EXAMPLE',
        ];

        $requestCookies = [];
        $isOnline = true;

        $this->session = Utils::loadCurrentSession(
            $requestHeaders,
            $requestCookies,
            $isOnline
        );
        dd($this->session);
    }

    public function channelDetail()
    {
        return ShopifyChannel::where("channel_id",$this->channel_id)->first();
    }

    /**
     * Throttles API calls to comply with Shopify's rate limit.
     * Introduces a delay if necessary to avoid exceeding the limit.
     */
    public function throttleApiCall(): void
    {
        $currentTime = microtime(true);
        $this->lastApiCallTimes[] = $currentTime;

        // Delay execution if making requests too quickly
        if (count($this->lastApiCallTimes) > 2) {
            $timeDifference = $currentTime - $this->lastApiCallTimes[0];
            if ($timeDifference < 1) {
                usleep((1 - $timeDifference) * 1000000);
            }
            array_shift($this->lastApiCallTimes);
        }
    }
}
