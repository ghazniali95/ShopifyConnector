<?php

namespace ShopifyConnector\App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ShopifyConnector\App\Contracts\ShopifyConnectorContract;

/**
 * Handles the connection and communication with the Shopify API.
 * Manages authentication, request formatting, and rate limiting for API calls.
 */
class ShopifyConnector implements ShopifyConnectorContract
{
    protected Client $client; // HTTP client for making requests to Shopify API.
    protected array $params = []; // Parameters for the request body.
    protected array $options = ['timeout' => 180]; // Parameters for the request body.
    private array $lastApiCallTimes = []; // Timestamps of the last API calls for rate limiting.

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
        $this->data["api_key"] = ($data["api_key"] ?? config('shopifyconnector.api_key'));
        $this->data["access_token"] = ($data["access_token"] ?? config('shopifyconnector.access_token'));
        $this->data["shop_url"] = ($data["shop_url"] ?? config('shopifyconnector.shop_url'));
        $this->data["api_version"] = ($data["api_version"] ?? config('shopifyconnector.api_version'));

        // Validate essential data
        if (empty($this->data["api_key"]) || empty($this->data["access_token"]) || empty($this->data["api_version"]) || empty($this->data["shop_url"])) {
            throw new Exception("shop url, api version, access token and api key must be provided");
        }

        $this->initializeClient();
    }

    /**
     * Sets the request body parameters.
     *
     * @param array $array Parameters for the request.
     * @return $this for method chaining.
     */
    public function setBody(array $array)
    {
        $this->params = $array;
        return $this;
    }

    /**
     * Retrieves the current request body parameters.
     *
     * @return array The parameters.
     */
    public function getBody(): ?array
    {
        return $this->params ?? [];
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

    /**
     * Sends a GET request to the given URL with provided parameters.
     *
     * @param string $url The API endpoint.
     * @param array $params Additional parameters.
     * @return array The JSON-decoded response body.
     */
    public function get($url, $params = null): ?array
    {
        // Throttle before making the API call
        $this->throttleApiCall();
        if ($params != null) {
            // ... Inside a method where you want to merge:
            $this->options = array_merge($this->options, ['json' => $params]);
        }
        try {
            $response = $this->client->request('GET', $url, $this->options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            // Return JSON response or throw an exception if no response is present
            return $this->handleException($e);
        }
    }

    /**
     * Sends a POST request to the given URL with provided parameters.
     *
     * @param string $url The API endpoint.
     * @param array $params Additional parameters.
     * @return array The JSON-decoded response body.
     */
    public function post($url, $params = []): ?array
    {
        // Throttle before making the API call
        $this->throttleApiCall();
        if ($params != null) {
            // ... Inside a method where you want to merge:
            $this->options = array_merge($this->options, ['json' => $params]);
        }
        try {
            $response = $this->client->request('POST', $url, $this->options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            // Return JSON response or throw an exception if no response is present
            return $this->handleException($e);
        }
    }

    /**
     * Sends a PUT request to the given URL with provided parameters.
     *
     * @param string $url The API endpoint.
     * @param array $params Additional parameters.
     * @return array The JSON-decoded response body.
     */
    public function put($url, $params = []): ?array
    {
        // Throttle before making the API call
        $this->throttleApiCall();
        if ($params != null) {
            // ... Inside a method where you want to merge:
            $this->options = array_merge($this->options, ['json' => $params]);
        }
        try {
            $response = $this->client->request('PUT', $url, $this->options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            // Return JSON response or throw an exception if no response is present
            return $this->handleException($e);
        }
    }

    /**
     * Sends a DELETE request to the given URL with provided parameters.
     *
     * @param string $url The API endpoint.
     * @param array $params Additional parameters.
     * @return array The JSON-decoded response body.
     */
    public function delete($url, $params = [])
    {
        // Throttle before making the API call
        $this->throttleApiCall();
        if ($params != null) {
            // ... Inside a method where you want to merge:
            $this->options = array_merge($this->options, ['json' => $params]);
        }
        try {
            $response = $this->client->request('DELETE', $url, $this->options);

            // Check if the response status code indicates a successful deletion
            if ($response->getStatusCode() === 204 || $response->getStatusCode() === 200) {
                return ['success' => true, 'response' => json_decode($response->getBody(), true)];
            }

            // Handle cases where the deletion was not successful
            return ['success' => false, 'response' => json_decode($response->getBody(), true)];
        } catch (GuzzleException $e) {
            // Return JSON response or throw an exception if no response is present
            return $this->handleException($e);
        }
    }

    /**
     * Handles exceptions from API requests.
     * Extracts and returns the response body if available, otherwise throws an exception.
     *
     * @param GuzzleException $e The caught exception.
     * @return array The JSON-decoded response body.
     * @throws Exception if no response is associated with the error.
     */
    private function handleException(GuzzleException $e)
    {
        if ($e->hasResponse()) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            return json_decode($responseBody, true);
        } else {
            throw new Exception("Error communicating with Shopify: " . $e->getMessage(), $e->getCode());
        }
    }
}
