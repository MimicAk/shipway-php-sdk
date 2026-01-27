<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Validation exception
 * 
 * Thrown for HTTP 422 (Unprocessable Entity) responses
 */
class ValidationException extends ApiException
{
    private array $errors = [];
    private array $failedRules = [];
    private array $errorFields = [];

    public function __construct(
        string $message = "Validation failed",
        int $statusCode = 422,
        array $errors = [],
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $apiEndpoint = null,
        ?string $httpMethod = null,
        ?array $requestData = null,
        ?array $responseHeaders = null
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
        
        $this->errors = $errors;
        $this->parseErrors($errors);
    }

    private function parseErrors(array $errors): void
    {
        foreach ($errors as $field => $error) {
            if (is_string($error)) {
                $this->errorFields[$field] = [$error];
                $this->failedRules[$field] = ['general' => $error];
            } elseif (is_array($error)) {
                $this->errorFields[$field] = $error;
                $this->failedRules[$field] = $error;
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorFields(): array
    {
        return $this->errorFields;
    }

    public function getFailedRules(): array
    {
        return $this->failedRules;
    }

    public function hasErrorForField(string $field): bool
    {
        return isset($this->errorFields[$field]);
    }

    public function getErrorsForField(string $field): array
    {
        return $this->errorFields[$field] ?? [];
    }

    public function getFirstErrorForField(string $field): ?string
    {
        $errors = $this->getErrorsForField($field);
        return !empty($errors) ? reset($errors) : null;
    }

    public function getErrorSummary(): array
    {
        $summary = [];
        foreach ($this->errorFields as $field => $errors) {
            $summary[$field] = reset($errors);
        }
        return $summary;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['errors'] = $this->errors;
        $array['error_fields'] = $this->errorFields;
        $array['failed_rules'] = $this->failedRules;
        $array['error_summary'] = $this->getErrorSummary();
        return $array;
    }

    public function __toString(): string
    {
        $str = parent::__toString();
        
        if (!empty($this->errors)) {
            $str .= "\nValidation Errors:\n";
            foreach ($this->getErrorSummary() as $field => $error) {
                $str .= "  - {$field}: {$error}\n";
            }
        }
        
        return $str;
    }
}