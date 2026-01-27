<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking;

// {
//   "success": true,
//   "message": "Order has been added successfully.",
//   "awb_response": {
//     "success": true,
//     "message": "AWB No. assigned Successfully",
//     "AWB": "1333110020164",
//     "carrier_id": "3411",
//     "shipping_url": "https://app.shipway.com/shipping_labels/a3359e03e70ae96dd4f97cfe788fa859_thermal.pdf"
//   }
// }


class OrderResponse
{

    public bool $success;
    public string $message;
    public ?array $awb_response = null;

    /**
     * Create OrderResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->success = $data['success'] ?? false;
        $response->message = $data['message'] ?? '';
        $response->awb_response = $data['awb_response'] ?? null;
        return $response;
    }
}

class awb_response
{
    public bool $success;
    public string $message;
    public ?string $AWB = null;
    public ?string $carrier_id = null;
    public ?string $shipping_url = null;

    /**
     * Create AWBResponse from array
     */
    public static function fromArray(array $data): self
    {
        $awbResponse = new self();
        $awbResponse->success = $data['success'] ?? false;
        $awbResponse->message = $data['message'] ?? '';
        $awbResponse->AWB = $data['AWB'] ?? null;
        $awbResponse->carrier_id = $data['carrier_id'] ?? null;
        $awbResponse->shipping_url = $data['shipping_url'] ?? null;
        return $awbResponse;
    }
}