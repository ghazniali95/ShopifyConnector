<?php

namespace  Ghazniali95\ShopifyConnector\App\Contracts;

/**
 * Interface for Shopify Connector.
 *
 * This interface defines the contract for a service that facilitates
 * communication and operations with the Shopify API.
 */
interface ShopifyConnectorContract
{
    /**
     * Set the body for the API request.
     *
     * This method is responsible for setting up the request body
     * that will be sent to Shopify. It could involve formatting
     * data or adding necessary parameters.
     *
     * @return void
     */
    public function setBody(array $array);

    /**
     * get the body for the API request.
     *
     * This method is responsible for geting up the request body
     * that will be sent to Shopify. It could involve formatting
     * data or adding necessary parameters.
     *
     * @return void
     */
    public function getBody(): ?array;

/**
 * Sends a GET request to a specified Shopify API endpoint.
 *
 * This method is used for retrieving information from Shopify.
 *
 * @param string $url The API endpoint URL.
 * @param array $params Optional parameters for the GET request.
 * @return array Returns an array containing the JSON-decoded response body.
 */
public function get($url, $params = []): ?array;

/**
 * Sends a POST request to a specified Shopify API endpoint.
 *
 * This method is typically used for creating new resources in Shopify,
 * like products, variants, etc.
 *
 * @param string $url The API endpoint URL.
 * @param array $params Parameters for the POST request.
 * @return array Returns an array containing the JSON-decoded response body.
 */
public function post($url, $params = []): ?array;

/**
 * Sends a PUT request to a specified Shopify API endpoint.
 *
 * This method is generally used for updating existing resources in Shopify.
 *
 * @param string $url The API endpoint URL.
 * @param array $params Parameters for the PUT request.
 * @return array Returns an array containing the JSON-decoded response body.
 */
public function put($url, $params = []): ?array;

/**
 * Sends a DELETE request to a specified Shopify API endpoint.
 *
 * This method is used for deleting resources like products, variants, etc., from Shopify.
 * It typically returns minimal or no content upon successful execution.
 *
 * @param string $url The API endpoint URL.
 * @param array $params Optional parameters for the DELETE request.
 * @return array Returns an array indicating the success or failure of the deletion.
 */
public function delete($url, $params = []);


    /**
     * Initialize the HTTP client for Shopify API.
     *
     * This method should handle the initialization of the HTTP client
     * used for making requests to the Shopify API. It may involve setting
     * up base URIs, authentication headers, etc.
     *
     * @return void
     */
    public function initializeClient(): void;

    /**
     * Implement API call rate limiting.
     *
     * This method should manage the rate of API calls to adhere to
     * Shopify's rate limits. It ensures the connector does not exceed
     * the number of allowed requests in a given time frame.
     *
     * @return void
     */
    public function throttleApiCall(): void;
}
