<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;

/**
 * Shipments API resource
 */
class Shipments extends AbstractResource
{
    public function __construct(HttpClient $httpClient)
    {
        parent::__construct($httpClient, '/shipments');
    }

    /**
     * Create a new shipment
     */
    public function create(array $data): array
    {
        $response = $this->post($data);
        return $response['data'];
    }

    /**
     * Get shipment by ID
     */
    public function getById(string $id): array
    {
        $response = $this->get($id);
        return $response['data'];
    }

    /**
     * Get shipment by AWB number
     */
    public function getByAwb(string $awbNumber): array
    {
        $response = $this->get("awb/{$awbNumber}");
        return $response['data'];
    }

    /**
     * List shipments with filters
     */
    public function list(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $query = array_merge($filters, [
            'page' => $page,
            'per_page' => $perPage,
        ]);

        $response = $this->get('', $query);

        return [
            'data' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? [],
        ];
    }

    /**
     * Update shipment
     */
    public function update(string $id, array $data): array
    {
        $response = $this->put($id, $data);
        return $response['data'];
    }

    /**
     * Cancel a shipment
     */
    public function cancel(string $id, string $reason = ''): bool
    {
        $data = $reason ? ['cancellation_reason' => $reason] : [];
        $response = $this->post($data, "/{$id}/cancel");
        return $response['success'] ?? false;
    }

    /**
     * Generate shipment manifest
     */
    public function generateManifest(array $shipmentIds): ?string
    {
        $response = $this->post(['shipment_ids' => $shipmentIds], '/generate_manifest');
        return $response['data']['manifest_url'] ?? null;
    }
}