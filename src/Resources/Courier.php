<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;

/**
 * Courier API resource
 */
class Courier extends AbstractResource
{
    public function __construct(HttpClient $httpClient)
    {
        parent::__construct($httpClient, '/couriers');
    }

    /**
     * Get list of available couriers
     */
    public function list(array $filters = []): array
    {
        $response = $this->get('', $filters);
        return $response['data'] ?? [];
    }

    /**
     * Get courier by ID
     */
    public function getById(string $id): array
    {
        $response = $this->get($id);
        return $response['data'];
    }

    /**
     * Get courier services
     */
    public function getServices(string $courierId): array
    {
        $response = $this->get("/{$courierId}/services");
        return $response['data'] ?? [];
    }

    /**
     * Get courier rates
     */
    public function getRates(array $data): array
    {
        $response = $this->post($data, '/rates');
        return $response['data'] ?? [];
    }

    /**
     * Get courier tracking
     */
    public function track(string $courierId, string $awbNumber): array
    {
        $response = $this->get("/{$courierId}/track/{$awbNumber}");
        return $response['data'];
    }
}