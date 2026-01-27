<?php

namespace MimicAk\ShipwayPhpSdk\Models;

/**
 * Order model for Shipway API v2
 */
class Order
{
    // Required fields
    public string $order_id;
    public array $products = [];
    public string $email;

    // 
    public ?int $carrier_id = null;
    public ?int $warehouse_id = null;
    public ?int $return_warehouse_id = null;

    // Billing address
    public string $billing_address;
    public ?string $billing_address2 = null;
    public string $billing_city;
    public string $billing_state;
    public string $billing_country;
    public string $billing_firstname;
    public string $billing_lastname;
    public string $billing_phone;
    public string $billing_zipcode;
    public ?string $billing_latitude = null;
    public ?string $billing_longitude = null;

    // Shipping address
    public string $shipping_address;
    public ?string $shipping_address2 = null;
    public string $shipping_city;
    public string $shipping_state;
    public string $shipping_country;
    public string $shipping_firstname;
    public string $shipping_lastname;
    public string $shipping_phone;
    public string $shipping_zipcode;
    public ?string $shipping_latitude = null;
    public ?string $shipping_longitude = null;

    // Order details
    public ?string $ewaybill = null;
    public ?string $discount = "0";
    public ?string $shipping = "0";
    public string $order_total;
    public ?string $gift_card_amt = "0";
    public ?string $taxes = "0";
    public string $payment_type;  // P = Prepaid, C = COD

    // Package dimensions
    public ?string $order_weight = null;
    public ?string $box_length = null;
    public ?string $box_breadth = null;
    public ?string $box_height = null;

    // Timestamps
    public ?string $order_date = null;

    /**
     * Create Order from array (for API response)
     */
    public static function fromArray(array $data): self
    {
        $order = new self();

        // Required fields
        $order->order_id = $data['order_id'] ?? '';
        $order->email = $data['email'] ?? '';

        // carrier and warehouse IDs
        $order->carrier_id = $data['carrier_id'] ?? '';
        $order->warehouse_id = $data['warehouse_id'] ?? '';
        $order->return_warehouse_id = $data['return_warehouse_id'] ?? '';

        // Products
        if (isset($data['products']) && is_array($data['products'])) {
            $order->products = array_map([Product::class, 'fromArray'], $data['products']);
        }

        // Billing address
        $order->billing_address = $data['billing_address'] ?? '';
        $order->billing_address2 = $data['billing_address2'] ?? null;
        $order->billing_city = $data['billing_city'] ?? '';
        $order->billing_state = $data['billing_state'] ?? '';
        $order->billing_country = $data['billing_country'] ?? '';
        $order->billing_firstname = $data['billing_firstname'] ?? '';
        $order->billing_lastname = $data['billing_lastname'] ?? '';
        $order->billing_phone = $data['billing_phone'] ?? '';
        $order->billing_zipcode = $data['billing_zipcode'] ?? '';
        $order->billing_latitude = $data['billing_latitude'] ?? null;
        $order->billing_longitude = $data['billing_longitude'] ?? null;

        // Shipping address
        $order->shipping_address = $data['shipping_address'] ?? '';
        $order->shipping_address2 = $data['shipping_address2'] ?? null;
        $order->shipping_city = $data['shipping_city'] ?? '';
        $order->shipping_state = $data['shipping_state'] ?? '';
        $order->shipping_country = $data['shipping_country'] ?? '';
        $order->shipping_firstname = $data['shipping_firstname'] ?? '';
        $order->shipping_lastname = $data['shipping_lastname'] ?? '';
        $order->shipping_phone = $data['shipping_phone'] ?? '';
        $order->shipping_zipcode = $data['shipping_zipcode'] ?? '';
        $order->shipping_latitude = $data['shipping_latitude'] ?? null;
        $order->shipping_longitude = $data['shipping_longitude'] ?? null;

        // Order details
        $order->ewaybill = $data['ewaybill'] ?? null;
        $order->discount = $data['discount'] ?? "0";
        $order->shipping = $data['shipping'] ?? "0";
        $order->order_total = $data['order_total'] ?? "0";
        $order->gift_card_amt = $data['gift_card_amt'] ?? "0";
        $order->taxes = $data['taxes'] ?? "0";
        $order->payment_type = $data['payment_type'] ?? 'P';

        // Package dimensions
        $order->order_weight = $data['order_weight'] ?? null;
        $order->box_length = $data['box_length'] ?? null;
        $order->box_breadth = $data['box_breadth'] ?? null;
        $order->box_height = $data['box_height'] ?? null;

        // Timestamps
        $order->order_date = $data['order_date'] ?? date('Y-m-d H:i:s');

        return $order;
    }

    /**
     * Convert Order to array for API request
     */
    public function toArray(): array
    {
        $data = [
            'order_id' => $this->order_id,
            'products' => array_map(fn($product) => $product->toArray(), $this->products),

            'carrier_id' => $this->carrier_id,
            'warehouse_id' => $this->warehouse_id,
            'return_warehouse_id' => $this->return_warehouse_id,

            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'order_total' => $this->order_total,
            'gift_card_amt' => $this->gift_card_amt,
            'taxes' => $this->taxes,
            'payment_type' => $this->payment_type,
            'email' => $this->email,

            // Billing address
            'billing_address' => $this->billing_address,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_country' => $this->billing_country,
            'billing_firstname' => $this->billing_firstname,
            'billing_lastname' => $this->billing_lastname,
            'billing_phone' => $this->billing_phone,
            'billing_zipcode' => $this->billing_zipcode,

            // Shipping address
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_state' => $this->shipping_state,
            'shipping_country' => $this->shipping_country,
            'shipping_firstname' => $this->shipping_firstname,
            'shipping_lastname' => $this->shipping_lastname,
            'shipping_phone' => $this->shipping_phone,
            'shipping_zipcode' => $this->shipping_zipcode,
        ];

        // Optional fields
        if ($this->ewaybill) {
            $data['ewaybill'] = $this->ewaybill;
        }
        if ($this->billing_address2) {
            $data['billing_address2'] = $this->billing_address2;
        }
        if ($this->billing_latitude) {
            $data['billing_latitude'] = $this->billing_latitude;
        }
        if ($this->billing_longitude) {
            $data['billing_longitude'] = $this->billing_longitude;
        }
        if ($this->shipping_address2) {
            $data['shipping_address2'] = $this->shipping_address2;
        }
        if ($this->shipping_latitude) {
            $data['shipping_latitude'] = $this->shipping_latitude;
        }
        if ($this->shipping_longitude) {
            $data['shipping_longitude'] = $this->shipping_longitude;
        }
        if ($this->order_weight) {
            $data['order_weight'] = $this->order_weight;
        }
        if ($this->box_length) {
            $data['box_length'] = $this->box_length;
        }
        if ($this->box_breadth) {
            $data['box_breadth'] = $this->box_breadth;
        }
        if ($this->box_height) {
            $data['box_height'] = $this->box_height;
        }
        if ($this->order_date) {
            $data['order_date'] = $this->order_date;
        }

        return $data;
    }
}