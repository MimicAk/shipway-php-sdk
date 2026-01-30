<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\Carriers;


class PincodeAvailabilityResponse
{

    // {
//   "success": 1,
//   "error": "",
//   "message": [
//     {
//       "carrier_id": "345",
//       "name": "Xpressbees",
//       "carrier_title": "Xpress Bees Test credential",
//       "payment_type": "C"
//     },
//     {
//       "carrier_id": "345",
//       "name": "Xpressbees",
//       "carrier_title": "Xpress Bees Test credential",
//       "payment_type": "P"
//     },
//     {
//       "carrier_id": "403",
//       "name": "Pickrr",
//       "carrier_title": "Pickrr Swati test credential",
//       "payment_type": "C"
//     },
//     {
//       "carrier_id": "403",
//       "name": "Pickrr",
//       "carrier_title": "Pickrr Swati test credential",
//       "payment_type": "P"
//     }
//   ]
// }
    public bool $success;
    public string $error;
    public ?CarrierResponse $services = null;

    /**
     * Create PincodeAvailabilityResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->success = (bool) ($data['success'] ?? false);
        $response->error = $data['error'] ?? '';

        // Map the 'message' array to services
        $response->carriers = array_map(function ($item) {
            return CarrierResponse::fromArray($item);
        }, $data['message'] ?? []);

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
            'services' => $this->services ?? []
        ];
    }
}