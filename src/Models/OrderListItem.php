<?php

namespace MimicAk\ShipwayPhpSdk\Models;

class OrderListItem
{
    public string $order_id;
    public string $order_total;
    public string $shipping_cost;
    public string $other_charges;
    public string $discount;
    public string $payment_id;
    public string $payment_method;
    public string $b_firstname;
    public string $b_lastname;
    public string $b_address;
    public string $b_address_2;
    public string $b_city;
    public string $b_country;
    public string $b_state;
    public string $b_zipcode;
    public string $b_phone;
    public string $s_firstname;
    public string $s_lastname;
    public string $s_address;
    public string $s_address_2;
    public string $s_city;
    public string $s_state;
    public string $s_country;
    public string $s_zipcode;
    public string $s_phone;
    public string $weight;
    public string $box_length;
    public string $box_breadth;
    public string $box_height;
    public string $email;
    public string $status;
    public string $invoice_number;
    public string $name;
    public string $carrier_title;
    public string $tracking_number;
    public string $shipment_status;
    public ?string $shipment_status_name;
    public ?Address $pickup_address;
    public ?Address $return_address;
    public string $order_date;
    public string $ezyslip_order_id;
    public array $shipment_status_scan;
    public array $products;
    public array $order_tags;

    /**
     * Create OrderListItem from array
     */
    public static function fromArray(array $data): self
    {
        $order = new self();
        $order->order_id = $data['order_id'] ?? '';
        $order->order_total = $data['order_total'] ?? '';
        $order->shipping_cost = $data['shipping_cost'] ?? '';
        $order->other_charges = $data['other_charges'] ?? '';
        $order->discount = $data['discount'] ?? '';
        $order->payment_id = $data['payment_id'] ?? '';
        $order->payment_method = $data['payment_method'] ?? '';
        $order->b_firstname = $data['b_firstname'] ?? '';
        $order->b_lastname = $data['b_lastname'] ?? '';
        $order->b_address = $data['b_address'] ?? '';
        $order->b_address_2 = $data['b_address_2'] ?? '';
        $order->b_city = $data['b_city'] ?? '';
        $order->b_country = $data['b_country'] ?? '';
        $order->b_state = $data['b_state'] ?? '';
        $order->b_zipcode = $data['b_zipcode'] ?? '';
        $order->b_phone = $data['b_phone'] ?? '';
        $order->s_firstname = $data['s_firstname'] ?? '';
        $order->s_lastname = $data['s_lastname'] ?? '';
        $order->s_address = $data['s_address'] ?? '';
        $order->s_address_2 = $data['s_address_2'] ?? '';
        $order->s_city = $data['s_city'] ?? '';
        $order->s_state = $data['s_state'] ?? '';
        $order->s_country = $data['s_country'] ?? '';
        $order->s_zipcode = $data['s_zipcode'] ?? '';
        $order->s_phone = $data['s_phone'] ?? '';
        $order->weight = $data['weight'] ?? '';
        $order->box_length = $data['box_length'] ?? '';
        $order->box_breadth = $data['box_breadth'] ?? '';
        $order->box_height = $data['box_height'] ?? '';
        $order->email = $data['email'] ?? '';
        $order->status = $data['status'] ?? '';
        $order->invoice_number = $data['invoice_number'] ?? '';
        $order->name = $data['name'] ?? '';
        $order->carrier_title = $data['carrier_title'] ?? '';
        $order->tracking_number = $data['tracking_number'] ?? '';
        $order->shipment_status = $data['shipment_status'] ?? '';
        $order->shipment_status_name = $data['shipment_status_name'] ?? null;
        $order->order_date = $data['order_date'] ?? '';
        $order->ezyslip_order_id = $data['ezyslip_order_id'] ?? '';
        $order->order_tags = $data['order_tags'] ?? [];

        // Handle nested objects
        $order->pickup_address = isset($data['pickup_address']) ? Address::fromArray($data['pickup_address']) : null;
        $order->return_address = isset($data['return_address']) ? Address::fromArray($data['return_address']) : null;

        // Handle arrays of objects
        $order->shipment_status_scan = array_map(function ($scan) {
            return ShipmentStatusScan::fromArray($scan);
        }, $data['shipment_status_scan'] ?? []);

        $order->products = array_map(function ($product) {
            return Product::fromArray($product);
        }, $data['products'] ?? []);

        return $order;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'order_total' => $this->order_total,
            'shipping_cost' => $this->shipping_cost,
            'other_charges' => $this->other_charges,
            'discount' => $this->discount,
            'payment_id' => $this->payment_id,
            'payment_method' => $this->payment_method,
            'b_firstname' => $this->b_firstname,
            'b_lastname' => $this->b_lastname,
            'b_address' => $this->b_address,
            'b_address_2' => $this->b_address_2,
            'b_city' => $this->b_city,
            'b_country' => $this->b_country,
            'b_state' => $this->b_state,
            'b_zipcode' => $this->b_zipcode,
            'b_phone' => $this->b_phone,
            's_firstname' => $this->s_firstname,
            's_lastname' => $this->s_lastname,
            's_address' => $this->s_address,
            's_address_2' => $this->s_address_2,
            's_city' => $this->s_city,
            's_state' => $this->s_state,
            's_country' => $this->s_country,
            's_zipcode' => $this->s_zipcode,
            's_phone' => $this->s_phone,
            'weight' => $this->weight,
            'box_length' => $this->box_length,
            'box_breadth' => $this->box_breadth,
            'box_height' => $this->box_height,
            'email' => $this->email,
            'status' => $this->status,
            'invoice_number' => $this->invoice_number,
            'name' => $this->name,
            'carrier_title' => $this->carrier_title,
            'tracking_number' => $this->tracking_number,
            'shipment_status' => $this->shipment_status,
            'shipment_status_name' => $this->shipment_status_name,
            'pickup_address' => $this->pickup_address?->toArray(),
            'return_address' => $this->return_address?->toArray(),
            'order_date' => $this->order_date,
            'ezyslip_order_id' => $this->ezyslip_order_id,
            'shipment_status_scan' => array_map(fn($scan) => $scan->toArray(), $this->shipment_status_scan),
            'products' => array_map(fn($product) => $product->toArray(), $this->products),
            'order_tags' => $this->order_tags
        ];
    }
}