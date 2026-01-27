<?php

namespace MimicAk\ShipwayPhpSdk\Models;

class Address
{
    public string $contact_person_name;
    public string $title;
    public string $address_1;
    public string $address_2;
    public string $city;
    public string $state;
    public string $country;
    public string $pincode;

    /**
     * Create Address from array
     */
    public static function fromArray(array $data): self
    {
        $address = new self();
        $address->contact_person_name = $data['contact_person_name'] ?? '';
        $address->title = $data['title'] ?? '';
        $address->address_1 = $data['address_1'] ?? '';
        $address->address_2 = $data['address_2'] ?? '';
        $address->city = $data['city'] ?? '';
        $address->state = $data['state'] ?? '';
        $address->country = $data['country'] ?? '';
        $address->pincode = $data['pincode'] ?? '';
        return $address;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'contact_person_name' => $this->contact_person_name,
            'title' => $this->title,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'pincode' => $this->pincode
        ];
    }
}