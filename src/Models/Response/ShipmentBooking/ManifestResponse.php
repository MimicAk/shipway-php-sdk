<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking;

// Failure
// {
//   "order ids": [
//     "TEST123",
//     "test2",
//     "Test3"
//   ],
//   "error": true,
//   "success": false,
//   "message": "Order ids not found"
// }

// Success
// {
//   "status": true,
//   "message": "Manifest created successfully.",
//   "manifest ids": "685798",
//   "error_response": []
// }

class ManifestResponse
{
    public bool $status;
    public array $order_ids;

    public string $message;
    public ?string $manifest_ids = null;
    public ?array $error_response = null;

    /**
     * Create ManifestResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->status = $data['status'] ?? false;
        $response->order_ids = $data['order ids'] ?? [];
        $response->message = $data['message'] ?? '';
        $response->manifest_ids = $data['manifest ids'] ?? null;
        $response->error_response = $data['error_response'] ?? null;
        return $response;
    }
}