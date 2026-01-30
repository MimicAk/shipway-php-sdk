<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;
use MimicAk\ShipwayPhpSdk\Config\API;
use MimicAk\ShipwayPhpSdk\Models\Request\Warehouses\CreateWarehouse;

use MimicAk\ShipwayPhpSdk\Models\Response\Warehouses\CreateWarehouseResponse;

/**
 * Warehouse API resource for Shipway API
 */

class Warehouse extends AbstractResource
{
    public function __construct(HttpClient $httpClient)
    {
        parent::__construct($httpClient, API::WAREHOUSES);
    }

    /**
     * Create a new warehouse
     * POST https://app.shipway.com/api/v2/warehouses
     * 
     * @param CreateWarehouse $warehouseData Warehouse data
     * @return CreateWarehouseResponse API response
     */
    public function create(CreateWarehouse $warehouseData): CreateWarehouseResponse
    {
        $data = $warehouseData->toArray();
        $response = $this->post($data, '');

        return CreateWarehouseResponse::fromArray($response);
    }
}