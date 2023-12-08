<?php

namespace ShopifyConnector\App\Services;

use Exception;
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
class ProductService extends ShopifyConnector implements ProductServiceContract
{
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
        parent::__construct($data);
    }

    /**
     * Get products from Shopify.
     *
     * Fetches the list of products from Shopify using the Shopify API.
     * It throttles the API call to comply with Shopify's rate limit and
     * handles any exceptions that occur during the request.
     *
     * @return array An array of products fetched from Shopify.
     * @throws Exception If there is an issue fetching the products.
     */
    public function getProducts(): array
    {
        try {
            // Make a GET request to fetch products
            $response = $this->get('products.json');
            return $response;
        } catch (GuzzleException $e) {
            // Throw a new exception with a custom message if an error occurs
            throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Retrieve a specific product from Shopify.
     *
     * This method fetches a product by its ID from Shopify using a GET request.
     * It includes rate limiting to comply with Shopify's API usage constraints.
     * In case of a successful response, it returns the product details as an array.
     *
     * @param int $product_id The ID of the product to be retrieved.
     * @return array An array containing the details of the requested product.
     * @throws Exception If there is an issue in fetching the product.
     */
    public function getProduct($product_id): array
    {
        try {
            // Make a GET request to fetch the specified product
            $response = $this->get('products/' . $product_id . '.json');
            return $response;
        } catch (GuzzleException $e) {
            // Throw a new exception with a custom message if an error occurs
            throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get the count of all products in Shopify.
     *
     * Sends a GET request to Shopify to retrieve the total count of products.
     * This method also includes rate limiting. It returns the count of products
     * as an array, typically with a single key-value pair.
     *
     * @return array An array containing the count of products.
     * @throws Exception If there is an issue in fetching the product count.
     */
    public function getCount()
    {
        try {
             // Make a GET request to fetch the count of products
            $response = $this->get('products/count.json');
            return $response;
        } catch (GuzzleException $e) {
            // Throw a new exception with a custom message if an error occurs
            throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create a product in Shopify.
     *
     * Sends a POST request to Shopify to create a new product. It uses the
     * parameters set in the class to construct the product data. This method
     * also handles rate limiting and exceptions that may occur during the request.
     *
     * @return array The response from Shopify after creating the product.
     * @throws Exception If there is an issue in creating the product.
     */
    public function createProduct(): array
    {
        try {
            // Make a POST request to create a new product
           $response = $this->post('products.json', $this->getBody());
           return $response;
       } catch (GuzzleException $e) {
           // Throw a new exception with a custom message if an error occurs
           throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
       }
    }

    /**
     * Update a product in Shopify.
     *
     * Sends a request to update a product in Shopify. The product to be updated is
     * identified by the provided product ID. The method sends the update parameters
     * set in the class. Handles rate limiting and exceptions during the request.
     *
     * @param int $product_id The ID of the product to be updated.
     * @return array The response from Shopify after updating the product.
     * @throws Exception If there is an issue in updating the product.
     */
    public function updateProduct($product_id): array
    {
        try {
            // Make a request to update the specified product
           $response = $this->put('products/' . $product_id . '.json', $this->getBody());
           return $response;
       } catch (GuzzleException $e) {
           // Throw a new exception with a custom message if an error occurs
           throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
       }
    }

    public function deleteProduct($product_id): array
    {
        try {
            // Make a request to update the specified product
           $response = $this->delete('products/' . $product_id . '.json');
           return $response;
       } catch (GuzzleException $e) {
           // Throw a new exception with a custom message if an error occurs
           throw new Exception("Error fetching products from Shopify: " . $e->getMessage(), $e->getCode());
       }
    }
}
