<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking;

class OrderOperationSingleResponse
{
    public string $order_id;
    public bool $error;
    public bool $success;
    public string $message;

    /**
     * Create OrderOperationSingleResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->order_id = $data['order_id'] ?? '';
        $response->error = (bool) ($data['error'] ?? false);
        $response->success = (bool) ($data['success'] ?? true);
        $response->message = $data['message'] ?? '';
        return $response;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'error' => $this->error,
            'success' => $this->success,
            'message' => $this->message
        ];
    }
}

class OrderOperationResponse
{
    /** @var OrderOperationSingleResponse[] */
    public array $responses = [];
    public bool $overall_success = true;
    public int $success_count = 0;
    public int $failure_count = 0;

    /**
     * Create BatchMarkOnHoldResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->responses = array_map(function ($item) {
            return OrderOperationSingleResponse::fromArray($item);
        }, $data);

        // Calculate overall metrics
        $response->success_count = count(array_filter($response->responses, fn($r) => $r->success));
        $response->failure_count = count(array_filter($response->responses, fn($r) => !$r->success));
        $response->overall_success = $response->failure_count === 0;

        return $response;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'responses' => array_map(fn($r) => $r->toArray(), $this->responses),
            'overall_success' => $this->overall_success,
            'success_count' => $this->success_count,
            'failure_count' => $this->failure_count,
            'total_count' => count($this->responses)
        ];
    }

    /**
     * Get all successful responses
     */
    public function getSuccessful(): array
    {
        return array_filter($this->responses, fn($r) => $r->success);
    }

    /**
     * Get all failed responses
     */
    public function getFailed(): array
    {
        return array_filter($this->responses, fn($r) => !$r->success);
    }

    /**
     * Get response by order ID
     */
    public function getByOrderId(string $orderId): ?OrderOperationSingleResponse
    {
        foreach ($this->responses as $response) {
            if ($response->order_id === $orderId) {
                return $response;
            }
        }
        return null;
    }

    /**
     * Get all order IDs
     */
    public function getOrderIds(): array
    {
        return array_map(fn($r) => $r->order_id, $this->responses);
    }

    /**
     * Get success message for an order
     */
    public function getMessageForOrder(string $orderId): ?string
    {
        $response = $this->getByOrderId($orderId);
        return $response ? $response->message : null;
    }

    /**
     * Check if all operations were successful
     */
    public function isAllSuccessful(): bool
    {
        return $this->overall_success;
    }

    /**
     * Check if any operation failed
     */
    public function hasFailures(): bool
    {
        return $this->failure_count > 0;
    }

    /**
     * Get a summary of the operation
     */
    public function getSummary(): array
    {
        return [
            'total_orders' => count($this->responses),
            'successful' => $this->success_count,
            'failed' => $this->failure_count,
            'success_rate' => $this->success_count > 0 ?
                round(($this->success_count / count($this->responses)) * 100, 2) : 0,
            'status' => $this->overall_success ? 'COMPLETE_SUCCESS' : 'PARTIAL_FAILURE'
        ];
    }
}