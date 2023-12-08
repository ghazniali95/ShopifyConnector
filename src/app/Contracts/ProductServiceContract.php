<?php

namespace ShopifyConnector\App\Contracts;

interface ProductServiceContract
{
    /**
     * Retrieve a list of products from Shopify.
     *
     * This method should be implemented to fetch and return an array of products.
     * The specific details of the implementation may vary, but it typically involves
     * making a GET request to the Shopify API.
     *
     * @return array An array of products.
     */
    public function getProducts();

    /**
     * Retrieve a specific product by its ID from Shopify.
     *
     * This method should be implemented to fetch and return details of a single product.
     * It requires the product ID as a parameter and typically involves making a GET request
     * to the Shopify API for that specific product.
     *
     * @param int $product_id The ID of the product to retrieve.
     * @return array An array containing the details of the requested product.
     */
    public function getProduct($product_id);

    /**
     * Get the total count of products in Shopify.
     *
     * This method should be implemented to return the count of all products available
     * in the Shopify store. It usually involves making a GET request to a specific
     * Shopify API endpoint that provides this count.
     *
     * @return array An array containing the count of products.
     */
    public function getCount();

    /**
     * Create a new product in Shopify.
     *
     * This method should be implemented to handle the creation of a new product.
     * It typically involves sending a POST request to the Shopify API with the product
     * data and returning the response from the API.
     *
     * @return array The response from Shopify after creating the product.
     */
    public function createProduct();

    /**
     * Update an existing product in Shopify.
     *
     * This method should be implemented to handle updates to an existing product.
     * It requires the product ID and the updated data, typically involving a PUT request
     * to the Shopify API and returning the API's response.
     *
     * @param int $product_id The ID of the product to update.
     * @return array The response from Shopify after updating the product.
     */
    public function updateProduct($product_id);

    /**
     * Delete a product from Shopify.
     *
     * This method should handle the deletion of a product from Shopify.
     * It requires the product ID and typically involves sending a DELETE request
     * to the Shopify API, returning the API's response upon successful deletion.
     *
     * @param int $product_id The ID of the product to be deleted.
     * @return array The response after deleting the product.
     */
    public function deleteProduct($product_id);
}
