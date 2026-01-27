<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * General API exception
 */
class ApiException extends ShipwayException
{
    private ?int $statusCode = null;
    private ?string $apiEndpoint = null;
    private ?string $httpMethod = null;
    private ?array $requestData = null;
    private ?array $responseHeaders = null;

    public function __construct(
        string $message = "",
        int $statusCode = 0,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $apiEndpoint = null,
        ?string $httpMethod = null,
        ?array $requestData = null,
        ?array $responseHeaders = null
    ) {
        parent::__construct($message, $statusCode, $previous, $context, $errorCode, $errorData);
        $this->statusCode = $statusCode;
        $this->apiEndpoint = $apiEndpoint;
        $this->httpMethod = $httpMethod;
        $this->requestData = $requestData;
        $this->responseHeaders = $responseHeaders;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getApiEndpoint(): ?string
    {
        return $this->apiEndpoint;
    }

    public function getHttpMethod(): ?string
    {
        return $this->httpMethod;
    }

    public function getRequestData(): ?array
    {
        return $this->requestData;
    }

    public function getResponseHeaders(): ?array
    {
        return $this->responseHeaders;
    }

    public function isClientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    public function isServerError(): bool
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['status_code'] = $this->statusCode;
        $array['api_endpoint'] = $this->apiEndpoint;
        $array['http_method'] = $this->httpMethod;
        $array['is_client_error'] = $this->isClientError();
        $array['is_server_error'] = $this->isServerError();

        // Don't include potentially sensitive data in array output
        if ($this->requestData) {
            $array['request_data_summary'] = array_keys($this->requestData);
        }

        return $array;
    }
}