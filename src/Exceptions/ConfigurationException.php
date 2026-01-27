<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Configuration exception
 * 
 * Thrown for invalid or missing configuration
 */
class ConfigurationException extends ShipwayException
{
    private ?string $configKey = null;
    private ?array $availableConfigs = null;
    private ?string $configType = null;

    public function __construct(
        string $message = "Configuration error",
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $configKey = null,
        ?array $availableConfigs = null,
        ?string $configType = null
    ) {
        parent::__construct($message, $code, $previous, $context, $errorCode, $errorData);

        $this->configKey = $configKey;
        $this->availableConfigs = $availableConfigs;
        $this->configType = $configType;
    }

    public function getConfigKey(): ?string
    {
        return $this->configKey;
    }

    public function getAvailableConfigs(): ?array
    {
        return $this->availableConfigs;
    }

    public function getConfigType(): ?string
    {
        return $this->configType;
    }

    public function isMissingConfig(): bool
    {
        return stripos($this->getMessage(), 'missing') !== false
            || stripos($this->getMessage(), 'required') !== false;
    }

    public function isInvalidConfig(): bool
    {
        return stripos($this->getMessage(), 'invalid') !== false
            || stripos($this->getMessage(), 'must be') !== false;
    }

    public function getSuggestedAction(): string
    {
        if ($this->isMissingConfig()) {
            $action = "Please provide the missing configuration.";
            if ($this->configKey) {
                $action .= " Missing key: '{$this->configKey}'";
            }
            return $action;
        } elseif ($this->isInvalidConfig()) {
            $action = "Please fix the invalid configuration.";
            if ($this->configKey) {
                $action .= " Invalid key: '{$this->configKey}'";
            }
            if ($this->availableConfigs) {
                $action .= " Available options: " . implode(', ', $this->availableConfigs);
            }
            return $action;
        }

        return "Review your configuration settings.";
    }
}