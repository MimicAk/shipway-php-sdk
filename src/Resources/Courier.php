<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;
use MimicAk\ShipwayPhpSdk\Config\API;
use MimicAk\ShipwayPhpSdk\Models\Request\Carriers\GetCarrierRates;
use MimicAk\ShipwayPhpSdk\Models\Response\Carriers\GetCarrierRatesResponse;
use MimicAk\ShipwayPhpSdk\Models\Response\Carriers\GetCarriersResponse;
use MimicAk\ShipwayPhpSdk\Models\Response\Carriers\PincodeAvailabilityResponse;

/**
 * Courier API resource
 */
class Courier extends AbstractResource
{
    public function __construct(HttpClient $httpClient)
    {
        parent::__construct($httpClient, 'api');
    }

    /**
     * Get list of available couriers
     */
    public function list(): GetCarriersResponse
    {

        $this->resourcePath = API::GET_CARRIERS;

        $response = $this->get('', []);
        return GetCarriersResponse::fromArray($response);
    }

    /**
     * Get courier by ID
     */
    public function getById(string $id): GetCarriersResponse
    {
        $this->resourcePath = API::GET_CARRIERS;
        $response = $this->get('', [$id]);
        return GetCarriersResponse::fromArray($response);
    }

    /**
     * Get courier services
     */
    public function getPincodeAvailability(string $pincode, string $paymentType = ''): PincodeAvailabilityResponse
    {
        // $this->resourcePath = API::PINCODE_SERVICEABILITY;
        $response = $this->get(API::PINCODE_SERVICEABILITY, ['pincode' => $pincode, 'payment_type' => $paymentType]);
        return PincodeAvailabilityResponse::fromArray($response);
    }

    /**
     * @param GetCarrierRates $data
     * return 
     * Get Shipway carrier rates
     */
    public function getCarrierRates(GetCarrierRates $data): GetCarrierRatesResponse
    {
        // $this->resourcePath = API::SHIPWAY_CARRIER_RATES;
        $response = $this->get(API::SHIPWAY_CARRIER_RATES, $data->toArray());

        // return $response;
        // var_dump($response);
        return GetCarrierRatesResponse::fromArray($response);
    }


    /**
     * Track shipment by AWB number
     * @param string $awbNumber The Air Waybill number(s) to track. For single shipment tracking, provide one AWB number. Maximum recommended: 100 AWB numbers per request for optimal performance.
     * @param int $trackingHistory 	Controls whether historical tracking events are included in the response.
     */

    public function trackShipment(string $awbNumber, int $trackingHistory = 0)
    {
        $config = $this->httpClient->getConfig();

        $config->setBaseUrl(API::TRACKING_BASE_URL);
        $httpClient = new HttpClient($config);

        $httpClient->request('GET', '', [
            'query' => [
                'awb' => $awbNumber,
                'tracking_history' => $trackingHistory
            ]
        ]);
    }
}
