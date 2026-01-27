<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Authentication/Authorization exception
 * 
 * Thrown for HTTP 401 (Unauthorized) and 403 (Forbidden) responses
 */
class AuthenticationException extends ApiException
{
    private ?string $authType = null;
    private ?string $realm = null;
    private ?string $challenge = null;

    public function __construct(
        string $message = "Authentication failed",
        int $statusCode = 401,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $apiEndpoint = null,
        ?string $httpMethod = null,
        ?array $requestData = null,
        ?array $responseHeaders = null,
        ?string $authType = null,
        ?string $realm = null,
        ?string $challenge = null
    ) {
        parent::__construct(
            $message,
            $statusCode,
            $previous,
            $context,
            $errorCode,
            $errorData,
            $apiEndpoint,
            $httpMethod,
            $requestData,
            $responseHeaders
        );
        
        $this->authType = $authType;
        $this->realm = $realm;
        $this->challenge = $challenge;
    }

    public function getAuthType(): ?string
    {
        return $this->authType;
    }

    public function getRealm(): ?string
    {
        return $this->realm;
    }

    public function getChallenge(): ?string
    {
        return $this->challenge;
    }

    public function isUnauthorized(): bool
    {
        return $this->getCode() === 401;
    }

    public function isForbidden(): bool
    {
        return $this->getCode() === 403;
    }

    public function getSuggestedAction(): string
    {
        if ($this->isUnauthorized()) {
            return "Check your API credentials (email and license key).";
        } elseif ($this->isForbidden()) {
            return "You don't have permission to access this resource. Check your account permissions.";
        }
        
        return "Review your authentication configuration.";
    }
}