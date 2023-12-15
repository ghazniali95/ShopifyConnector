<?php

namespace ShopifyConnector\App\Services;

use Exception;
use Shopify\Rest\Admin2023_07\Product;
use GuzzleHttp\Exception\GuzzleException;
use ShopifyConnector\App\Contracts\ProductServiceContract;

/**
 * Product Service Class.
 *
 * This class extends the ShopifyConnector to provide functionalities
 * specific to fetching products from Shopify. It implements the
 * ProductServiceContract interface, defining the contract for product-related
 * operations.
 */
class ProductService extends Product
{
    use ShopifyConnector;

    /**
     * Create a new ProductService instance.
     *
     * Calls the parent constructor to initialize the ShopifyConnector
     * with the provided data.
     *
     * @param array $data Configuration data necessary for the ShopifyConnector.
     */
    public function __construct(array $data)
    {
        $this->orgainzation_id = $data['orgainzation_id'];
        $this->channel_id = $data['channel_id'];

    }


}
