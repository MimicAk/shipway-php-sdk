<?php

namespace MimicAk\ShipwayPhpSdk\Models;

/**
 * Product model for Shipway API
 */
class Product
{
    public string $product;
    public string $price;
    public ?string $product_code = null;
    public string $product_quantity = "1";
    public string $discount = "0";
    public ?string $tax_rate = null;
    public ?string $tax_title = null;

    public static function fromArray(array $data): self
    {
        $product = new self();
        $product->product = $data['product'] ?? '';
        $product->price = $data['price'] ?? '0';
        $product->product_code = $data['product_code'] ?? null;
        $product->product_quantity = $data['product_quantity'] ?? '1';
        $product->discount = $data['discount'] ?? '0';
        $product->tax_rate = $data['tax_rate'] ?? null;
        $product->tax_title = $data['tax_title'] ?? null;
        return $product;
    }

    public function toArray(): array
    {
        $data = [
            'product' => $this->product,
            'price' => $this->price,
            'product_quantity' => $this->product_quantity,
            'discount' => $this->discount,
        ];

        if ($this->product_code) {
            $data['product_code'] = $this->product_code;
        }
        if ($this->tax_rate) {
            $data['tax_rate'] = $this->tax_rate;
        }
        if ($this->tax_title) {
            $data['tax_title'] = $this->tax_title;
        }

        return $data;
    }
}