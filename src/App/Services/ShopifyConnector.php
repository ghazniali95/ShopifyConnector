<?php

namespace  Ghazniali95\ShopifyConnector\App\Services;

use Exception;
use GuzzleHttp\Client;
use App\Models\Channel\ShopifyChannel;
use Ghazniali95\ShopifyConnector\App\Classes\Utils;
use Ghazniali95\ShopifyConnector\App\Classes\Context;
use Ghazniali95\ShopifyConnector\App\Classes\Auth\Session;
use Ghazniali95\ShopifyConnector\App\Classes\Auth\FileSessionStorage;

/**
 * Handles the connection and communication with the Shopify API.
 * Manages authentication, request formatting, and rate limiting for API calls.
 */
class ShopifyConnector
{ 
    public $orgainzation_id;
    public $channel_id;
    protected Session $session;

    /**
     * Constructor: Sets up the ShopifyConnector with configuration data.
     * Initializes the HTTP client for API requests.
     *
     * @param array $data Configuration data (API key, access token, shop URL, and API version).
     * @throws Exception if required configuration data is missing.
     */
    public function __construct(protected array $data = [])
    { 
        if(count($data) > 0){
            $this->orgainzation_id = $data['orgainzation_id'];
            $this->channel_id = $data['channel_id']; 
        } 
    } 
}
