<!-- {
  "error": false,
  "success": true,
  "message": "Selected orders have been unassigned for 1 out of 1 successfully..",
  "invalid_tracking_numbers": "",
  "shipment_success_tracking_numbers": "81126908074",
  "shipment_failed_tracking_numbers": ""
} -->

<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking;

class ShipmentCancelResponse
{
    public bool $error;
    public bool $success;
    public string $message;
    public string $invalid_tracking_numbers;
    public string $shipment_success_tracking_numbers;
    public string $shipment_failed_tracking_numbers;

    /**
     * Create ShipmentCancelResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->error = (bool) ($data['error'] ?? false);
        $response->success = (bool) ($data['success'] ?? true);
        $response->message = $data['message'] ?? '';
        $response->invalid_tracking_numbers = $data['invalid_tracking_numbers'] ?? '';
        $response->shipment_success_tracking_numbers = $data['shipment_success_tracking_numbers'] ?? '';
        $response->shipment_failed_tracking_numbers = $data['shipment_failed_tracking_numbers'] ?? '';
        return $response;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'error' => $this->error,
            'success' => $this->success,
            'message' => $this->message,
            'invalid_tracking_numbers' => $this->invalid_tracking_numbers,
            'shipment_success_tracking_numbers' => $this->shipment_success_tracking_numbers,
            'shipment_failed_tracking_numbers' => $this->shipment_failed_tracking_numbers
        ];
    }
}