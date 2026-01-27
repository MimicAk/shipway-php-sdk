<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Resource-specific exception
 * 
 * Thrown for resource-related errors (not found, conflict, etc.)
 */
class ResourceException extends ApiException
{
    private ?string $resourceType = null;
    private ?string $resourceId = null;
    private ?string $resourceAction = null;
    private ?array $resourceData = null;

    public function __construct(
        string $message = "Resource error",
        int $statusCode = 0,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $apiEndpoint = null,
        ?string $httpMethod = null,
        ?array $requestData = null,
        ?array $responseHeaders = null,
        ?string $resourceType = null,
        ?string $resourceId = null,
        ?string $resourceAction = null,
        ?array $resourceData = null
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

        $this->resourceType = $resourceType;
        $this->resourceId = $resourceId;
        $this->resourceAction = $resourceAction;
        $this->resourceData = $resourceData;
    }

    public function getResourceType(): ?string
    {
        return $this->resourceType;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function getResourceAction(): ?string
    {
        return $this->resourceAction;
    }

    public function getResourceData(): ?array
    {
        return $this->resourceData;
    }

    public function isNotFound(): bool
    {
        return $this->getCode() === 404;
    }

    public function isConflict(): bool
    {
        return $this->getCode() === 409;
    }

    public function isGone(): bool
    {
        return $this->getCode() === 410;
    }

    public function isUnavailable(): bool
    {
        return $this->getCode() === 503;
    }

    public function getSuggestedAction(): string
    {
        if ($this->isNotFound()) {
            return "The requested resource was not found. Check the resource ID and type.";
        } elseif ($this->isConflict()) {
            return "The resource is in a conflicting state. Try updating or recreating it.";
        } elseif ($this->isGone()) {
            return "The resource is no longer available.";
        } elseif ($this->isUnavailable()) {
            return "The resource is temporarily unavailable. Please try again later.";
        }

        return "Check the resource details and try again.";
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['resource_type'] = $this->resourceType;
        $array['resource_id'] = $this->resourceId;
        $array['resource_action'] = $this->resourceAction;
        $array['is_not_found'] = $this->isNotFound();
        $array['is_conflict'] = $this->isConflict();
        $array['is_gone'] = $this->isGone();
        return $array;
    }
}