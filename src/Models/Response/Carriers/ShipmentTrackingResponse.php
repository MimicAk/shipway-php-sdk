<?php

namespace MimicAk\ShipwayPhpSdk\Models\Response\Tracking;

/**
 * Shipment Tracking Response Item
 */
class ShipmentTrackingResponse
{
    /**
     * AWB number (string or numeric from API)
     *
     * @var string|int
     */
    public string|int $awb;

    /**
     * Shipment tracking details
     */
    public TrackingDetails $tracking_details;

    /**
     * Hydrate object from API response array
     */
    public static function fromArray(array $data): self
    {
        $self = new self();
        $self->awb = $data['awb'];
        $self->tracking_details = TrackingDetails::fromArray($data['tracking_details']);

        return $self;
    }
}


/**
 * Tracking Details
 */
class TrackingDetails
{
    /**
     * Shipment status (DEL, INT, UND, etc.)
     */
    public string $shipment_status;

    /**
     * Shipment details (usually one entry per AWB)
     *
     * @var ShipmentDetail[]
     */
    public array $shipment_details = [];

    /**
     * Tracking URL
     */
    public string $track_url;

    /**
     * Tracking scan history
     *
     * @var TrackingHistory[]
     */
    public array $tracking_history = [];

    public static function fromArray(array $data): self
    {
        $self = new self();
        $self->shipment_status = $data['shipment_status'];
        $self->track_url = $data['track_url'];

        foreach ($data['shipment_details'] ?? [] as $detail) {
            $self->shipment_details[] = ShipmentDetail::fromArray($detail);
        }

        foreach ($data['tracking_history'] ?? [] as $history) {
            $self->tracking_history[] = TrackingHistory::fromArray($history);
        }

        return $self;
    }
}


/**
 * Shipment Detail
 */
class ShipmentDetail
{
    public string $courier_id;
    public string $courier_name;
    public string $order_id;
    public ?string $pickup_date = null;
    public ?string $delivered_date = null;
    public string $weight;
    public int $packages;
    public string $current_status;
    public ?string $delivered_to = null;
    public string $destination;
    public string $consignee_name;
    public string $origin;

    public static function fromArray(array $data): self
    {
        $self = new self();

        $self->courier_id = $data['courier_id'];
        $self->courier_name = $data['courier_name'];
        $self->order_id = $data['order_id'];
        $self->pickup_date = $data['pickup_date'] ?? null;
        $self->delivered_date = $data['delivered_date'] ?? null;
        $self->weight = $data['weight'];
        $self->packages = (int) $data['packages'];
        $self->current_status = $data['current_status'];
        $self->delivered_to = $data['delivered_to'] ?? null;
        $self->destination = $data['destination'];
        $self->consignee_name = $data['consignee_name'];
        $self->origin = $data['origin'];

        return $self;
    }
}


/**
 * Tracking History Entry
 */
class TrackingHistory
{
    public string $status;
    public string $location;
    public string $timestamp;
    public string $remarks;

    public static function fromArray(array $data): self
    {
        $self = new self();

        $self->status = $data['status'];
        $self->location = $data['location'];
        $self->timestamp = $data['timestamp'];
        $self->remarks = $data['remarks'];

        return $self;
    }
}