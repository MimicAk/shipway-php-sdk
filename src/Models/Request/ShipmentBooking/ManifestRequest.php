<?php

// Shipway provides Manifest order API to interact with Shipway system. This Rest API enables merchants to create Manifest order in Shipway system.

namespace MimicAk\ShipwayPhpSdk\Models\Request\ShipmentBooking;

class ManifestRequest
{
    /**
     * @var array List of order IDs to be included in the manifest
     */
    public array $order_ids;

    /**
     * Constructor
     *
     * @param array $order_ids List of order IDs
     */
    public function __construct(array $order_ids)
    {
        $this->order_ids = $order_ids;
    }

    /**
     * Convert ManifestRequest to array
     *
     * @return array
     * 
     */
    public function toArray(): array
    {
        return [
            'order ids' => $this->order_ids,
        ];
    }
}