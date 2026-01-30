<?php

namespace MimicAk\ShipwayPhpSdk;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;
use MimicAk\ShipwayPhpSdk\Config\Configuration;
use MimicAk\ShipwayPhpSdk\Resources\Orders;
use MimicAk\ShipwayPhpSdk\Resources\Shipments;
use MimicAk\ShipwayPhpSdk\Resources\Tracking;
use MimicAk\ShipwayPhpSdk\Resources\Courier;
use MimicAk\ShipwayPhpSdk\Resources\Webhooks;

/**
 * Main entry point for Shipway PHP SDK
 */
class Shipway
{
    private HttpClient $httpClient;
    private array $resources = [];

    /**
     * Create a new Shipway API client
     * 
     * @param string $userEmail Your Shipway registered email
     * @param string $apiKey Your Shipway license key (found in Profile > Manage profile)
     * @param array $config Additional configuration options
     */
    public function __construct(string $userEmail, string $apiKey, array $config = [])
    {
        $configuration = Configuration::fromArray(array_merge($config, [
            'user_email' => $userEmail,
            'api_key' => $apiKey
        ]));
        $this->httpClient = new HttpClient($configuration);
    }

    /**
     * Factory method to create a new Shipway API client
     * 
     * @param string $userEmail Your Shipway registered email
     * @param string $apiKey Your Shipway license key
     * @param array $config Additional configuration options
     * @return self
     */
    public static function create(string $userEmail, string $apiKey, array $config = []): self
    {
        return new self($userEmail, $apiKey, $config);
    }

    /**
     * Create a new Shipway API client from environment variables
     * 
     * Requires SHIPWAY_USER_EMAIL and SHIPWAY_API_KEY environment variables
     * 
     * @param array $config Additional configuration options
     * @return self
     */
    public static function createFromEnv(array $config = []): self
    {
        $configuration = Configuration::fromEnv();
        return new self(
            $configuration->getUserEmail(),
            $configuration->getApiKey(),
            $config
        );
    }

    /**
     * Get Orders resource
     */
    public function orders(): Orders
    {
        if (!isset($this->resources['orders'])) {
            $this->resources['orders'] = new Orders($this->httpClient);
        }
        return $this->resources['orders'];
    }

    /**
     * Get Shipments resource
     */
    public function shipments(): Shipments
    {
        if (!isset($this->resources['shipments'])) {
            $this->resources['shipments'] = new Shipments($this->httpClient);
        }
        return $this->resources['shipments'];
    }


    /**
     * Get Courier resource
     */
    public function courier(): Courier
    {
        if (!isset($this->resources['courier'])) {
            $this->resources['courier'] = new Courier($this->httpClient);
        }
        return $this->resources['courier'];
    }

    /**
     * Get the underlying HTTP client
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * Get the current configuration
     */
    public function getConfig(): Configuration
    {
        return $this->httpClient->getConfig();
    }
}