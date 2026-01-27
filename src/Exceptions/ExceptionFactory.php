<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Throwable;

/**
 * Factory for creating exceptions from API responses
 */
class ExceptionFactory
{
    /**
     * Create appropriate exception from HTTP response
     */
    public static function createFromResponse(
        ResponseInterface $response,
        ?string $apiEndpoint = null,
        ?string $httpMethod = null,
        ?array $requestData = null
    ): ApiException {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $responseHeaders = $response->getHeaders();

        // Restore the stream for further reading
        $response->getBody()->rewind();

        $data = json_decode($body, true);
        $message = $data['message'] ?? $response->getReasonPhrase();
        $errorCode = $data['error_code'] ?? null;
        $errors = $data['errors'] ?? [];
        $errorData = $data['error_data'] ?? null;

        switch ($statusCode) {
            case 400:
                return new ApiException(
                    $message,
                    $statusCode,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders
                );

            case 401:
            case 403:
                $authType = $responseHeaders['www-authenticate'][0] ?? null;
                $realm = null;
                $challenge = null;

                if ($authType) {
                    if (preg_match('/realm="([^"]+)"/', $authType, $matches)) {
                        $realm = $matches[1];
                    }
                    if (preg_match('/challenge="([^"]+)"/', $authType, $matches)) {
                        $challenge = $matches[1];
                    }
                }

                return new AuthenticationException(
                    $message,
                    $statusCode,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders,
                    $authType,
                    $realm,
                    $challenge
                );

            case 404:
                return new ResourceException(
                    $message,
                    $statusCode,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders,
                    self::extractResourceType($apiEndpoint),
                    self::extractResourceId($apiEndpoint, $requestData),
                    $httpMethod
                );

            case 409:
                return new ResourceException(
                    $message,
                    $statusCode,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders,
                    self::extractResourceType($apiEndpoint),
                    self::extractResourceId($apiEndpoint, $requestData),
                    $httpMethod,
                    $requestData
                );

            case 422:
                return new ValidationException(
                    $message,
                    $statusCode,
                    $errors,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders
                );

            case 429:
                $retryAfter = isset($responseHeaders['retry-after'][0])
                    ? (int) $responseHeaders['retry-after'][0]
                    : null;

                return new RateLimitException(
                    $message,
                    $statusCode,
                    $retryAfter,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders
                );

            case 500:
            case 502:
            case 503:
            case 504:
                return new ApiException(
                    'Service unavailable: ' . $message,
                    $statusCode,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders
                );

            default:
                return new ApiException(
                    $message,
                    $statusCode,
                    null,
                    [],
                    $errorCode,
                    $errorData,
                    $apiEndpoint,
                    $httpMethod,
                    $requestData,
                    $responseHeaders
                );
        }
    }

    /**
     * Create exception from Guzzle RequestException
     */
    public static function createFromRequestException(
        RequestException $e,
        ?string $apiEndpoint = null,
        ?string $httpMethod = null,
        ?array $requestData = null
    ): ShipwayException {
        if ($e->hasResponse()) {
            return self::createFromResponse(
                $e->getResponse(),
                $apiEndpoint,
                $httpMethod,
                $requestData
            );
        }

        // Network/connection error - return NetworkException
        return new NetworkException(
            $e->getMessage(),
            $e->getCode(),
            $e,
            [],
            'NETWORK_ERROR',
            null,
            $apiEndpoint ? parse_url($apiEndpoint, PHP_URL_HOST) : null,
            $apiEndpoint ? parse_url($apiEndpoint, PHP_URL_PORT) : null,
            null,
            'HTTP'
        );
    }

    /**
     * Create configuration exception
     */
    public static function createConfigurationException(
        string $message,
        ?string $configKey = null,
        ?array $availableConfigs = null
    ): ConfigurationException {
        return new ConfigurationException(
            $message,
            0,
            null,
            [],
            'CONFIG_ERROR',
            null,
            $configKey,
            $availableConfigs,
            'shipway'
        );
    }

    /**
     * Create webhook exception
     */
    public static function createWebhookException(
        string $message,
        ?string $webhookId = null,
        ?string $webhookEvent = null,
        ?string $webhookUrl = null
    ): WebhookException {
        return new WebhookException(
            $message,
            0,
            null,
            [],
            'WEBHOOK_ERROR',
            null,
            $webhookId,
            $webhookEvent,
            $webhookUrl
        );
    }

    /**
     * Extract resource type from API endpoint
     */
    private static function extractResourceType(?string $apiEndpoint): ?string
    {
        if (!$apiEndpoint) {
            return null;
        }

        $parts = explode('/', trim($apiEndpoint, '/'));
        if (!empty($parts)) {
            return $parts[0];
        }

        return null;
    }

    /**
     * Extract resource ID from API endpoint or request data
     */
    private static function extractResourceId(?string $apiEndpoint, ?array $requestData): ?string
    {
        // Try to extract from URL path
        if ($apiEndpoint && preg_match('/\/([a-f0-9-]+)(?:\/|$)/i', $apiEndpoint, $matches)) {
            return $matches[1];
        }

        // Try to extract from request data
        if ($requestData) {
            foreach (['id', 'order_id', 'shipment_id', 'webhook_id'] as $key) {
                if (isset($requestData[$key])) {
                    return (string) $requestData[$key];
                }
            }
        }

        return null;
    }
}