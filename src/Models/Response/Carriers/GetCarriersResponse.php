<?php

// {
//   "success": 1,
//   "error": "",
//   "message": [
//     {
//       "id": "18708",
//       "name": "Sequel Logistics",
//       "reverse_status": true,
//       "ndr_status": false,
//       "aggregator_carrier": false,
//       "carrier_title": "A Sequel swati test credential"
//     },
//     {
//       "id": "1",
//       "name": "Bluedart",
//       "reverse_status": true,
//       "ndr_status": true,
//       "aggregator_carrier": false,
//       "carrier_title": "Bluedart test"
//     },]}


namespace MimicAk\ShipwayPhpSdk\Models\Response\Carriers;

class GetCarriersResponse
{

    /** @var CarrierResponse[] */
    public array $carriers = [];


    public bool $success;
    public string $error;

    /**
     * Create GetCarriersResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();

        $response->success = (bool) ($data['success'] ?? false);
        $response->error = $data['error'] ?? '';

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
            'carriers' => array_map(fn($c) => $c->toArray(), $this->carriers)
        ];
    }

}