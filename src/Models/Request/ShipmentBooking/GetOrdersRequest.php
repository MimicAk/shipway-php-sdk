<?php

namespace MimicAk\ShipwayPhpSdk\Models\Request\ShipmentBooking;

// orderid	with order ID	string
// awb_number	With AWB number	string
// tags	With order tags (COD,Prime)	string
// date_from	Get Orders from date (yyyy-mm-dd)	date
// date_to	Get Orders to date (yyyy-mm-dd)	date
// status	Order status (O, A, E, G)	string
// page	1	numeric
// shipment_status	Shipment Status (DEL, INT, UND, RTO, RTD, CAN, SCH, ONH, OOD, NFI, NFIDS, RSCH, ROOP, RPKP, RDEL, RINT, RPSH, RCAN, RCLO, RSMD, PCAN, ROTH, RPF)	string
// new_shipment_status		string

class GetOrdersRequest
{

    public ?string $orderid = null;
    public ?string $awb_number = null;
    public ?string $tags = null;
    public ?string $date_from = null;
    public ?string $date_to = null;
    public ?OrderStatus $status = null;
    public ?int $page = null;
    public ?ShipmentStatus $shipment_status = null;
    public ?string $new_shipment_status = null;

    /**
     * Convert GetOrdersRequest to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'orderid' => $this->orderid,
            'awb_number' => $this->awb_number,
            'tags' => $this->tags,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'status' => $this->status,
            'page' => $this->page,
            'shipment_status' => $this->shipment_status,
            'new_shipment_status' => $this->new_shipment_status,
        ];
    }

}


class OrderStatus
{
    // Order Statuses
//     Status      Description
// O:-          New order
// A:-          Processing
// E:-          Manifested
// G:-          Dispatched


    public const STATUS_NEW = 'O';
    public const STATUS_PROCESSING = 'A';
    public const STATUS_MANIFESTED = 'E';
    public const STATUS_DISPATCHED = 'G';
}

class ShipmentStatus
{
    // Shipment Statuses
    // Shipment Status Description
//     Shipment Status      Description
// DEL:-                Delivered
// INT:-                In Transit
// UND:-                Undelivered
// RTO:-                RTO
// RTD:-                RTO Delivered
// CAN:-                Canceled
// SCH:-                Shipment Booked
// ONH:-                On Hold
// OOD:-                Out For Delivery
// NFI:-                Status Pending
// NFIDS:-              NFID
// RSCH:-               Pickup Scheduled
// ROOP:-               Out for Pickup
// RPKP:-               Shipment Picked Up
// RDEL:-               Return Delivered
// RINT:-               Return In Transit
// RPSH:-               Pickup Rescheduled
// RCAN:-               Return Request Cancelled
// RCLO:-               Return Request Closed
// RSMD:-               Pickup Delayed
// PCAN:-               Pickup Cancelled
// ROTH:-               Others
// RPF:-                Pickup Failed


    public const STATUS_DELIVERED = 'DEL';
    public const STATUS_IN_TRANSIT = 'INT';
    public const STATUS_UNDELIVERED = 'UND';
    public const STATUS_RTO = 'RTO';
    public const STATUS_RTO_DELIVERED = 'RTD';
    public const STATUS_CANCELED = 'CAN';
    public const STATUS_SHIPMENT_BOOKED = 'SCH';
    public const STATUS_ON_HOLD = 'ONH';
    public const STATUS_OUT_FOR_DELIVERY = 'OOD';
    public const STATUS_STATUS_PENDING = 'NFI';
    public const STATUS_NFID = 'NFIDS';
    public const STATUS_PICKUP_SCHEDULED = 'RSCH';
    public const STATUS_OUT_FOR_PICKUP = 'ROOP';
    public const STATUS_SHIPMENT_PICKED_UP = 'RPKP';
    public const STATUS_RETURN_DELIVERED = 'RDEL';
    public const STATUS_RETURN_IN_TRANSIT = 'RINT';
    public const STATUS_PICKUP_RESCHEDULED = 'RPSH';
    public const STATUS_RETURN_REQUEST_CANCELLED = 'RCAN';
    public const STATUS_RETURN_REQUEST_CLOSED = 'RCLO';
    public const STATUS_PICKUP_DELAYED = 'RSMD';
    public const STATUS_PICKUP_CANCELLED = 'PCAN';
    public const STATUS_OTHERS = 'ROTH';
    public const STATUS_PICKUP_FAILED = 'RPF';

}