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


    /**
     * Initializes the HTTP client for Shopify API requests.
     * Configures base URI and headers including authentication details.
     */
    public function initializeSession($data): void
    {
        $this->orgainzation_id = $data['orgainzation_id'];
        $this->channel_id = $data['channel_id']; 
        Context::initialize(
            env("SHOPIFY_API_KEY","3bda7be4f9789a1f43d84e3d8f13fc22"),
            env("SHOPIFY_SHARED_SECRET","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"),
             env("SHOPIFY_SCOPES","write_products,read_inventory,write_inventory,read_locations"),
             env("APP_URL","http://localhost:8000/"),
             new FileSessionStorage(storage_path('shopify/sessions/')),
                 '2023-10',
                 true,
                 false,
                 null,
                 '',
                 null,
                [ $this->channelDetail()->shop]
     
             );
          if(!$this->session){
            $this->session = new Session(
                id: $this->channelDetail()->shop_id,
                shop: $this->channelDetail()->shop,
                isOnline: true,
                state:'NA'
            );
            $this->session->setAccessToken("Bearer ".$this->channelDetail()->access_token);
            $this->session->setScope(env("SHOPIFY_SCOPES","write_products,read_inventory,write_inventory,read_locations"));
          }  
    }

    public function channelDetail()
    {
        return ShopifyChannel::where("channel_id",$this->channel_id)->first();
    } 
}
