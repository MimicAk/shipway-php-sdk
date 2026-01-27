<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking;

use MimicAk\ShipwayPhpSdk\Models\OrderListItem;

class GetOrdersResponse
{
    public bool $success;
    public string $error;
    public ?array $orders = null;

    /**
     * Create GetOrdersResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->success = (bool) ($data['success'] ?? false);
        $response->error = $data['error'] ?? '';

        // Map the 'message' array to OrderListItem objects
        if (isset($data['message']) && is_array($data['message'])) {
            $response->orders = array_map(function ($orderData) {
                return OrderListItem::fromArray($orderData);
            }, $data['message']);
        }

        return $response;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'error' => $this->error,
            'orders' => $this->orders ? array_map(fn($order) => $order->toArray(), $this->orders) : []
        ];
    }

    /**
     * Check if the response was successful
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Get all orders
     */
    public function getOrders(): array
    {
        return $this->orders ?? [];
    }

    /**
     * Get order by ID
     */
    public function getOrderById(string $orderId): ?OrderListItem
    {
        foreach ($this->orders ?? [] as $order) {
            if ($order->order_id === $orderId) {
                return $order;
            }
        }
        return null;
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus(string $status): array
    {
        return array_filter($this->orders ?? [], fn($order) => $order->status === $status);
    }

    /**
     * Get orders by carrier
     */
    public function getOrdersByCarrier(string $carrierName): array
    {
        return array_filter($this->orders ?? [], fn($order) => $order->name === $carrierName);
    }
}