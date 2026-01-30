<?php

namespace MimicAk\ShipwayPhpSdk\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use MimicAk\ShipwayPhpSdk\Exceptions\ExceptionFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use MimicAk\ShipwayPhpSdk\Config\Configuration;
use MimicAk\ShipwayPhpSdk\Exceptions\ApiException;
use MimicAk\ShipwayPhpSdk\Exceptions\AuthenticationException;
use MimicAk\ShipwayPhpSdk\Exceptions\ValidationException;

/**
 * HTTP client wrapper for Shipway API
 */
class HttpClient
{
    private Client $client;
    private Configuration $config;
    private LoggerInterface $logger;

    public function __construct(Configuration $config, ?LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->logger = $logger ?? new NullLogger();

        $clientConfig = [
            'base_uri' => $config->getBaseUrl(),
            'timeout' => $config->getTimeout(),
            'headers' => $this->getDefaultHeaders(),
        ];

        if ($config->getDebug()) {
            $clientConfig['debug'] = fopen('php://stderr', 'w');
        }

        $this->client = new Client($clientConfig);
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }


    private function getDefaultHeaders(): array
    {
        // Shipway API uses Basic HTTP authentication
        // Format: Authorization: Basic {base64_encoded_email:license_key}
        $headers = [
            'Authorization' => 'Basic ' . $this->config->getToken(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => Configuration::USER_AGENT,
        ];

        if ($this->config->getPartnerCode()) {
            $headers['X-Partner-Code'] = $this->config->getPartnerCode();
        }

        return $headers;
    }

    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        $attempts = 0;
        $maxAttempts = $this->config->getRetryAttempts();

        $this->logger->debug('Shipway API Request', [
            'method' => $method,
            'uri' => $uri,
            'attempt' => $attempts + 1,
        ]);

        while ($attempts <= $maxAttempts) {
            try {
                $response = $this->client->request($method, $uri, $options);
                $this->logResponse($response);
                return $this->handleResponse($response);
            } catch (RequestException $e) {
                $attempts++;

                if ($attempts > $maxAttempts || !$this->isRetryable($e->getResponse())) {
                    $this->logger->error('Shipway API Request Failed', [
                        'method' => $method,
                        'uri' => $uri,
                        'attempt' => $attempts,
                        'error' => $e->getMessage(),
                    ]);
                    throw $this->handleException($e);
                }

                $this->logger->warning('Shipway API Request Retrying', [
                    'method' => $method,
                    'uri' => $uri,
                    'attempt' => $attempts,
                    'next_attempt' => $attempts + 1,
                ]);

                // Exponential backoff
                usleep((int) (1000000 * pow(2, $attempts)));
            }
        }

        throw new ApiException('Maximum retry attempts exceeded');
    }

    private function logResponse(ResponseInterface $response): void
    {
        $this->logger->debug('Shipway API Response', [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
        ]);
    }

    private function handleResponse(ResponseInterface $response): ResponseInterface
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 200 && $statusCode < 300) {
            return $response;
        }

        throw $this->createExceptionFromResponse($response);
    }

    private function createExceptionFromResponse(ResponseInterface $response): ApiException
    {
        return ExceptionFactory::createFromResponse(
            $response,
            $this->lastRequestUri ?? null,
            $this->lastRequestMethod ?? null,
            $this->lastRequestData ?? null
        );

    }

    private function isRetryable(?ResponseInterface $response): bool
    {
        if (!$response) {
            return true;
        }

        $statusCode = $response->getStatusCode();
        return in_array($statusCode, [429, 500, 502, 503, 504]);
    }

    private function handleException(RequestException $e): ApiException
    {
        if ($e->hasResponse()) {
            return $this->createExceptionFromResponse($e->getResponse());
        }

        return new ApiException($e->getMessage(), 0, $e);
    }
}