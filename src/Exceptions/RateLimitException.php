<?php

namespace MimicAk\ShipwayPhpSdk\Exceptions;

/**
 * Rate limit exception
 * 
 * Thrown for HTTP 429 (Too Many Requests) responses
 */
class RateLimitException extends ApiException
{
    private ?int $retryAfter = null;
    private ?int $rateLimitLimit = null;
    private ?int $rateLimitRemaining = null;
    private ?int $rateLimitReset = null;

    public function __construct(
        string $message = "Rate limit exceeded",
        int $statusCode = 429,
        ?int $retryAfter = null,
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

        $this->retryAfter = $retryAfter;
        $this->parseRateLimitHeaders($responseHeaders);
    }

    private function parseRateLimitHeaders(?array $headers): void
    {
        if (!$headers) {
            return;
        }

        foreach ($headers as $key => $value) {
            $normalizedKey = strtolower($key);
            $headerValue = is_array($value) ? reset($value) : $value;

            switch ($normalizedKey) {
                case 'x-ratelimit-limit':
                    $this->rateLimitLimit = (int) $headerValue;
                    break;
                case 'x-ratelimit-remaining':
                    $this->rateLimitRemaining = (int) $headerValue;
                    break;
                case 'x-ratelimit-reset':
                    $this->rateLimitReset = (int) $headerValue;
                    break;
                case 'retry-after':
                    if (is_numeric($headerValue)) {
                        $this->retryAfter = (int) $headerValue;
                    }
                    break;
            }
        }
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }

    public function getRateLimitLimit(): ?int
    {
        return $this->rateLimitLimit;
    }

    public function getRateLimitRemaining(): ?int
    {
        return $this->rateLimitRemaining;
    }

    public function getRateLimitReset(): ?int
    {
        return $this->rateLimitReset;
    }

    public function getRetryDateTime(): ?\DateTime
    {
        if ($this->rateLimitReset) {
            return (new \DateTime())->setTimestamp($this->rateLimitReset);
        }

        if ($this->retryAfter) {
            return (new \DateTime())->add(new \DateInterval("PT{$this->retryAfter}S"));
        }

        return null;
    }

    public function getWaitTimeSeconds(): ?int
    {
        if ($this->retryAfter) {
            return $this->retryAfter;
        }

        if ($this->rateLimitReset) {
            $now = time();
            return max(0, $this->rateLimitReset - $now);
        }

        return null;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['retry_after'] = $this->retryAfter;
        $array['rate_limit_limit'] = $this->rateLimitLimit;
        $array['rate_limit_remaining'] = $this->rateLimitRemaining;
        $array['rate_limit_reset'] = $this->rateLimitReset;
        $array['wait_time_seconds'] = $this->getWaitTimeSeconds();
        $array['retry_datetime'] = $this->getRetryDateTime()?->format('Y-m-d H:i:s');
        return $array;
    }

    public function __toString(): string
    {
        $str = parent::__toString();

        $waitTime = $this->getWaitTimeSeconds();
        if ($waitTime) {
            $str .= "\nPlease wait {$waitTime} seconds before retrying.";
        }

        if ($this->rateLimitLimit) {
            $str .= "\nRate Limit: {$this->rateLimitLimit} requests";
        }

        if ($this->rateLimitRemaining !== null) {
            $str .= "\nRemaining: {$this->rateLimitRemaining} requests";
        }

        return $str;
    }
}