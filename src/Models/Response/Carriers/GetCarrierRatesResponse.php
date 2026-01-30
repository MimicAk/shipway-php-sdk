<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\Carriers;


// {
//   "success": "success",
//   "rate_card": [
//     {
//       "carrier_id": 7377,
//       "courier_name": "Shipway Bluedart Express (0.5kg)",
//       "delivery_charge": 50,
//       "rto_charge": 50,
//       "charged_weight": 0.5,
//       "zone": 1
//     }
//   ]
// }

class GetCarrierRatesResponse
{
    /** @var CarrierResponse[] */
    public array $carriers = [];

    public bool $success;

    /**
     * Create GetCarrierRatesResponse from array
     */

    public static function fromArray(array $data): self
    {
        $response = new self();

        $response->success = (bool) ($data['success'] ?? false);

        $response->carriers = array_map(function ($item) {

            $item['courier_title'] = $item['courier_name'] ?? '';

            return CarrierResponse::fromArray($item);
        }, $data['rate_card'] ?? []);
        return $response;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'carriers' => array_map(fn($c) => $c->toArray(), $this->carriers)
        ];
    }

}