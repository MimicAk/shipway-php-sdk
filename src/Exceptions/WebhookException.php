<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Webhook-specific exception
 */
class WebhookException extends ShipwayException
{
    private ?string $webhookId = null;
    private ?string $webhookEvent = null;
    private ?string $webhookUrl = null;
    private ?string $signature = null;
    private ?string $payloadHash = null;

    public function __construct(
        string $message = "Webhook error",
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
        ?string $errorCode = null,
        ?array $errorData = null,
        ?string $webhookId = null,
        ?string $webhookEvent = null,
        ?string $webhookUrl = null,
        ?string $signature = null,
        ?string $payloadHash = null
    ) {
        parent::__construct($message, $code, $previous, $context, $errorCode, $errorData);

        $this->webhookId = $webhookId;
        $this->webhookEvent = $webhookEvent;
        $this->webhookUrl = $webhookUrl;
        $this->signature = $signature;
        $this->payloadHash = $payloadHash;
    }

    public function getWebhookId(): ?string
    {
        return $this->webhookId;
    }

    public function getWebhookEvent(): ?string
    {
        return $this->webhookEvent;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhookUrl;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function getPayloadHash(): ?string
    {
        return $this->payloadHash;
    }

    public function isSignatureInvalid(): bool
    {
        return stripos($this->getMessage(), 'signature') !== false
            || stripos($this->getMessage(), 'verification') !== false;
    }

    public function isDeliveryFailed(): bool
    {
        return stripos($this->getMessage(), 'delivery') !== false
            || stripos($this->getMessage(), 'failed to send') !== false;
    }

    public function getSuggestedAction(): string
    {
        if ($this->isSignatureInvalid()) {
            return "Check your webhook secret and ensure signatures are calculated correctly.";
        } elseif ($this->isDeliveryFailed()) {
            return "Check if your webhook URL is accessible and responding correctly.";
        }

        return "Review your webhook configuration and try again.";
    }
}