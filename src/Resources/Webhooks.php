<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Exceptions\ValidationException;

/**
 * Webhooks API resource
 */
class Webhooks extends AbstractResource
{
    public function __construct(HttpClient $httpClient)
    {
        parent::__construct($httpClient, '/webhooks');
    }

    /**
     * Create a new webhook
     */
    public function create(array $data): array
    {
        $this->validateWebhookData($data);
        $response = $this->post($data);
        return $response['data'];
    }

    /**
     * Get webhook by ID
     */
    public function getById(string $id): array
    {
        $response = $this->get($id);
        return $response['data'];
    }

    /**
     * List webhooks
     */
    public function list(array $filters = []): array
    {
        $response = $this->get('', $filters);
        return $response['data'] ?? [];
    }

    /**
     * Update webhook
     */
    public function update(string $id, array $data): array
    {
        $response = $this->put($id, $data);
        return $response['data'];
    }

    /**
     * Delete webhook
     */
    public function delete(string $id): bool
    {
        $response = $this->delete($id);
        return $response['success'] ?? false;
    }

    /**
     * Test webhook
     */
    public function test(string $id): bool
    {
        $response = $this->post([], "/{$id}/test");
        return $response['success'] ?? false;
    }

    /**
     * Validate webhook payload
     */
    public function validatePayload(array $payload, string $signature, string $secret): bool
    {
        $computedSignature = hash_hmac('sha256', json_encode($payload), $secret);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Validate webhook data
     */
    private function validateWebhookData(array $data): void
    {
        $required = ['url', 'events'];
        $missing = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new ValidationException(
                'Missing required fields: ' . implode(', ', $missing),
                422,
                ['missing_fields' => $missing]
            );
        }
    }
}