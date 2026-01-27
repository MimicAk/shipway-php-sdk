<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Base exception class for Shipway SDK
 */
class ShipwayException extends \Exception
{
    protected array $context = [];
    protected ?string $errorCode = null;
    protected ?array $errorData = null;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->errorCode = $errorCode;
        $this->errorData = $errorData;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorData(): ?array
    {
        return $this->errorData;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'error_code' => $this->errorCode,
            'error_data' => $this->errorData,
            'context' => $this->context,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTrace(),
        ];
    }

    public function __toString(): string
    {
        $str = parent::__toString();

        if ($this->errorCode) {
            $str = "[Error Code: {$this->errorCode}] " . $str;
        }

        if (!empty($this->context)) {
            $str .= "\nContext: " . json_encode($this->context, JSON_PRETTY_PRINT);
        }

        if (!empty($this->errorData)) {
            $str .= "\nError Data: " . json_encode($this->errorData, JSON_PRETTY_PRINT);
        }

        return $str;
    }
}