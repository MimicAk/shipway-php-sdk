<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Network/Connection exception
 * 
 * Thrown for connection failures, timeouts, etc.
 */
class NetworkException extends ShipwayException
{
    private ?string $host = null;
    private ?int $port = null;
    private ?float $timeout = null;
    private ?string $connectionType = null;

    public function __construct(
        string $message = "Network error occurred",
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $host = null,
        ?int $port = null,
        ?float $timeout = null,
        ?string $connectionType = null
    ) {
        parent::__construct($message, $code, $previous, $context, $errorCode, $errorData);

        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->connectionType = $connectionType;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getTimeout(): ?float
    {
        return $this->timeout;
    }

    public function getConnectionType(): ?string
    {
        return $this->connectionType;
    }

    public function isTimeout(): bool
    {
        return stripos($this->getMessage(), 'timeout') !== false
            || stripos($this->getMessage(), 'timed out') !== false;
    }

    public function isConnectionRefused(): bool
    {
        return stripos($this->getMessage(), 'connection refused') !== false
            || stripos($this->getMessage(), 'could not connect') !== false;
    }

    public function isDnsError(): bool
    {
        return stripos($this->getMessage(), 'could not resolve host') !== false
            || stripos($this->getMessage(), 'name or service not known') !== false;
    }

    public function getSuggestedAction(): string
    {
        if ($this->isTimeout()) {
            return "Increase the timeout value or check your network connection.";
        } elseif ($this->isConnectionRefused()) {
            return "Check if the Shipway API service is available and your firewall settings.";
        } elseif ($this->isDnsError()) {
            return "Check your DNS configuration and ensure api.shipway.in is reachable.";
        }

        return "Check your network connection and try again.";
    }
}