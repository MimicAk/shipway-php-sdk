<?php

namespace MimicAk\ShipwayPhpSdk\Client;

use Psr\Http\Message\ResponseInterface;
use MimicAk\ShipwayPhpSdk\Exceptions\ApiException;

/**
 * Handles API responses and converts them to standardized format
 */
class ResponseHandler
{
    public function handle(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        if (empty($body)) {
            return [
                'success' => $response->getStatusCode() >= 200 && $response->getStatusCode() < 300,
                'data' => [],
                'message' => null,
                'meta' => null,
                'errors' => null,
            ];
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                'Invalid JSON response from API: ' . json_last_error_msg(),
                $response->getStatusCode()
            );
        }

        // Handle Shipway API response format
        // Shipway typically returns: {success: true, data: {...}, message: "..."}
        // or for errors: {success: false, message: "...", errors: [...]}

        return [
            'success' => $data['success'] ?? ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300),
            'data' => $data['data'] ?? $data,
            'message' => $data['message'] ?? null,
            'meta' => $data['meta'] ?? $data['pagination'] ?? null,
            'errors' => $data['errors'] ?? null,
        ];
    }

    /**
     * Extract pagination data from response
     */
    public function getPagination(array $response): array
    {
        $meta = $response['meta'] ?? [];

        return [
            'current_page' => $meta['current_page'] ?? 1,
            'per_page' => $meta['per_page'] ?? 20,
            'total' => $meta['total'] ?? 0,
            'total_pages' => $meta['total_pages'] ?? 1,
            'has_next' => $meta['has_next'] ?? false,
            'has_previous' => $meta['has_previous'] ?? false,
        ];
    }
}