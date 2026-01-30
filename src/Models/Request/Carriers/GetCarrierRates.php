<?php

namespace MimicAk\ShipwayPhpSdk\Models\Request\Carriers;

/**
 * Get Carrier Rates Request
 *
 * API:
 * https://app.shipway.com/api/getshipwaycarrierrates
 *
 * Query Params:
 * - fromPincode (int, mandatory)
 * - toPincode (int, mandatory)
 * - paymentType (string: cod|prepaid, mandatory)
 * - length (int, optional, cm)
 * - breadth (int, optional, cm)
 * - height (int, optional, cm)
 * - weight (int, optional, kg)
 * - shipment_type (int, optional, only 1 = forward)
 * - cummulativePrice (int, optional, mandatory if COD)
 */
class GetCarrierRates
{
    public int $fromPincode;
    public int $toPincode;
    public string $paymentType;

    public ?int $length = null;
    public ?int $breadth = null;
    public ?int $height = null;
    public ?int $weight = null;
    public ?int $shipment_type = null;
    public ?int $cummulativePrice = null;

    /**
     * Convert GetCarrierRates to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'fromPincode' => $this->fromPincode,
            'toPincode' => $this->toPincode,
            'paymentType' => $this->paymentType,
            'length' => $this->length,
            'breadth' => $this->breadth,
            'height' => $this->height,
            'weight' => $this->weight,
            'shipment_type' => $this->shipment_type,
            'cummulativePrice' => $this->cummulativePrice,
        ];
    }
}
