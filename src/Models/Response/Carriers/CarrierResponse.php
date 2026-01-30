<?php

//  {
//       "id": "345",
//       "name": "Xpressbees",
//       "reverse_status": true,
//       "ndr_status": false,
//       "aggregator_carrier": false,
//       "carrier_title": "Xpress Bees Test credential"
//     } 


namespace MimicAk\ShipwayPhpSdk\Models\Response\Carriers;

class CarrierResponse
{
    public string $id;
    public string $name;
    public bool $reverse_status;
    public bool $ndr_status;
    public bool $aggregator_carrier;
    public string $carrier_title;

    public string $payment_type;

    public float $delivery_charge;
    public float $rto_charge;

    public float $charged_weight;

    public int $zone;

    /**
     * Create CarrierResponse from array
     */
    public static function fromArray(array $data): self
    {
        $response = new self();
        $response->id = $data['id'] ?? '';
        $response->name = $data['name'] ?? '';
        $response->reverse_status = (bool) ($data['reverse_status'] ?? false);
        $response->ndr_status = (bool) ($data['ndr_status'] ?? false);
        $response->aggregator_carrier = (bool) ($data['aggregator_carrier'] ?? false);
        $response->carrier_title = $data['carrier_title'] ?? '';
        $response->payment_type = $data['payment_type'] ?? '';

        $response->delivery_charge = (float) ($data['delivery_charge'] ?? 0);
        $response->rto_charge = (float) ($data['rto_charge'] ?? 0);
        $response->charged_weight = (float) ($data['charged_weight'] ?? 0);
        $response->zone = (int) ($data['zone'] ?? 0);

        return $response;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'reverse_status' => $this->reverse_status,
            'ndr_status' => $this->ndr_status,
            'aggregator_carrier' => $this->aggregator_carrier,
            'carrier_title' => $this->carrier_title,
            'payment_type' => $this->payment_type,
            
            'delivery_charge' => $this->delivery_charge,
            'rto_charge' => $this->rto_charge,
            'charged_weight' => $this->charged_weight,
            'zone' => $this->zone
        ];
    }
}