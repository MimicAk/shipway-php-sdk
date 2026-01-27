<?php

namespace MimicAk\ShipwayPhpSdk\Config;

/**
 * Configuration manager for Shipway API
 */
class Configuration
{
    private string $apiKey;
    private ?string $userEmail = null;
    private string $token;
    private string $baseUrl = 'https://app.shipway.com/api/';
    private int $timeout = 30;
    private int $retryAttempts = 3;
    private bool $debug = false;
    private ?string $partnerCode = null;
    private ?string $webhookSecret = null;

    private const USER_AGENT = 'MimicAk-Shipway-PHP-SDK/1.0';

    public function __construct(string $userEmail, string $apiKey)
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('API key is required');
        }
        if (empty($userEmail)) {
            throw new \InvalidArgumentException('User email is required');
        }

        $this->apiKey = $apiKey;
        $this->userEmail = $userEmail;

        // Create Base64 encoded token for Basic HTTP authentication
        // Format: "email:license_key"
        $this->token = base64_encode($userEmail . ':' . $apiKey);
    }

    /**
     * Create configuration from array
     */
    public static function fromArray(array $config): self
    {
        if (!isset($config['api_key'])) {
            throw new \InvalidArgumentException('API key is required in configuration');
        }
        if (!isset($config['user_email'])) {
            throw new \InvalidArgumentException('User email is required in configuration');
        }

        $instance = new self($config['user_email'], $config['api_key']);

        if (isset($config['base_url'])) {
            $instance->setBaseUrl($config['base_url']);
        }
        if (isset($config['timeout'])) {
            $instance->setTimeout($config['timeout']);
        }
        if (isset($config['retry_attempts'])) {
            $instance->setRetryAttempts($config['retry_attempts']);
        }
        if (isset($config['debug'])) {
            $instance->setDebug($config['debug']);
        }
        if (isset($config['partner_code'])) {
            $instance->setPartnerCode($config['partner_code']);
        }
        if (isset($config['webhook_secret'])) {
            $instance->setWebhookSecret($config['webhook_secret']);
        }

        return $instance;
    }

    /**
     * Create configuration from environment variables
     */
    public static function fromEnv(): self
    {
        $config = [
            'user_email' => getenv('SHIPWAY_USER_EMAIL') ?: '',
            'api_key' => getenv('SHIPWAY_API_KEY') ?: '',
        ];

        if (getenv('SHIPWAY_BASE_URL')) {
            $config['base_url'] = getenv('SHIPWAY_BASE_URL');
        }
        if (getenv('SHIPWAY_TIMEOUT')) {
            $config['timeout'] = (int) getenv('SHIPWAY_TIMEOUT');
        }
        if (getenv('SHIPWAY_PARTNER_CODE')) {
            $config['partner_code'] = getenv('SHIPWAY_PARTNER_CODE');
        }
        if (getenv('SHIPWAY_WEBHOOK_SECRET')) {
            $config['webhook_secret'] = getenv('SHIPWAY_WEBHOOK_SECRET');
        }

        return self::fromArray($config);
    }

    // Getters
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * Get the Base64 encoded authentication token.
     * 
     * The token is constructed using Shipway's Basic HTTP authentication mechanism.
     * It combines the Shipway Email (username) and License Key (password) in the format
     * "email:license_key" and encodes the resulting string in base64.
     * 
     * The License Key can be found in Shipway > Profile > Manage profile.
     * 
     * This token is used in the Authorization header for API requests.
     * 
     * @return string The Base64 encoded authentication token in the format "email:license_key"
     */
    public function getToken(): string
    {
        return $this->token;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getRetryAttempts(): int
    {
        return $this->retryAttempts;
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function getPartnerCode(): ?string
    {
        return $this->partnerCode;
    }

    public function getWebhookSecret(): ?string
    {
        return $this->webhookSecret;
    }

    // Setters with fluent interface
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function setRetryAttempts(int $retryAttempts): self
    {
        $this->retryAttempts = $retryAttempts;
        return $this;
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    public function setPartnerCode(?string $partnerCode): self
    {
        $this->partnerCode = $partnerCode;
        return $this;
    }

    public function setWebhookSecret(?string $webhookSecret): self
    {
        $this->webhookSecret = $webhookSecret;
        return $this;
    }
}