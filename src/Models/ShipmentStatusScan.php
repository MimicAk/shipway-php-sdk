<?php

namespace MimicAk\ShipwayPhpSdk\Models;

class ShipmentStatusScan
{
    public string $status;
    public string $datetime;
    public string $sub_status;

    /**
     * Create ShipmentStatusScan from array
     */
    public static function fromArray(array $data): self
    {
        $scan = new self();
        $scan->status = $data['status'] ?? '';
        $scan->datetime = $data['datetime'] ?? '';
        $scan->sub_status = $data['sub_status'] ?? '';
        return $scan;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'datetime' => $this->datetime,
            'sub_status' => $this->sub_status
        ];
    }
}