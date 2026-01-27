<?php

namespace MimicAk\ShipwayPhpSdk\Resources;

use MimicAk\ShipwayPhpSdk\Client\HttpClient;
use MimicAk\ShipwayPhpSdk\Config\API;
use MimicAk\ShipwayPhpSdk\Models\Order;
use MimicAk\ShipwayPhpSdk\Exceptions\ValidationException;
use MimicAk\ShipwayPhpSdk\Models\Request\ShipmentBooking\GetOrdersRequest;
use MimicAk\ShipwayPhpSdk\Models\Request\ShipmentBooking\ManifestRequest;
use MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking\GetOrdersResponse;
use MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking\ManifestResponse;
use MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking\OrderOperationResponse;
use MimicAk\ShipwayPhpSdk\Models\Response\ShipmentBooking\OrderResponse;

/**
 * Orders API resource for Shipway v2 API
 */
class Orders extends AbstractResource
{
    public function __construct(HttpClient $httpClient)
    {
        parent::__construct($httpClient, API::PUSH_ORDER);
    }

    /**
     * Push/Create a new order (Shipment Booking)
     * POST https://app.shipway.com/api/v2orders
     * 
     * @param array|Order $orderData Order data or Order object
     * @return OrderResponse API response
     */
    public function create($orderData): OrderResponse
    {
        if ($orderData instanceof Order) {
            $data = $orderData->toArray();
        } else {
            $data = $orderData;
        }

        $this->validateOrderData($data);
        $response = $this->post($data, '');

        return OrderResponse::fromArray($response);
    }


    /**
     * Handles order push with label generation.
     *
     * Includes courier selection, warehouse selection,
     * and return warehouse assignment for the order.
     * @param array|Order $orderData Order data or Order object
     * @return OrderResponse API response
     * 
     * @property int|null $carrier_id         Selected courier/carrier ID.
     * @property int|null $warehouse_id       Source warehouse ID.
     * @property int|null $return_warehouse_id Return warehouse ID.
     */
    public function createWithLabel(array|Order $orderData): OrderResponse
    {
        $data = $orderData instanceof Order
            ? $orderData->toArray()
            : $orderData;

        $response = $this->post($data, '');

        return OrderResponse::fromArray($response);
    }



    /**
     * Get orders
     * 
     * @param GetOrdersRequest $request The request object containing order ID
     * @return GetOrdersResponse Order data
     */
    public function getOrders(GetOrdersRequest $request): GetOrdersResponse
    {
        $this->resourcePath = API::GET_ORDER;

        $response = $this->get("", $request->toArray());

        return GetOrdersResponse::fromArray($response);
    }

    /**
     * Create Manifest for multiple orders
     * @param ManifestRequest $orderIds
     * @return ManifestResponse
     */
    public function createManifest(ManifestRequest $orderIds): ManifestResponse
    {
        $this->resourcePath = API::MANIFEST;

        $response = $this->post($orderIds->toArray(), '');

        return ManifestResponse::fromArray($response);
    }

    public function onHoldOrders(ManifestRequest $orderIds): OrderOperationResponse
    {
        $this->resourcePath = API::ONHOLD_ORDER;

        $response = $this->post($orderIds->toArray(), '');

        return OrderOperationResponse::fromArray($response);
    }

    public function cancelOrders(ManifestRequest $orderIds): OrderOperationResponse
    {
        $this->resourcePath = API::CANCEL_ORDER;

        $response = $this->post($orderIds->toArray(), '');

        return OrderOperationResponse::fromArray($response);
    }

    /**
     * Update an existing order
     * 
     * @param string $orderId The order ID
     * @param array $data Update data
     * @return array API response
     */
    public function update(string $orderId, array $data): array
    {
        return $this->put("/{$orderId}", $data);
    }

    /**
     * Cancel an order
     * 
     * @param string $orderId The order ID
     * @return array API response
     */
    public function cancel(string $orderId): array
    {
        return $this->delete("/{$orderId}");
    }

    /**
     * List orders with filters
     * 
     * @param array $filters Available filters:
     *   - status: Order status
     *   - from_date: From date (YYYY-MM-DD)
     *   - to_date: To date (YYYY-MM-DD)
     *   - courier: Courier name
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array List of orders
     */
    public function list(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $query = array_merge($filters, [
            'page' => $page,
            'per_page' => $perPage,
        ]);

        return parent::get('', $query);
    }

    /**
     * Validate order data before sending to API
     * 
     * @param array $data Order data
     * @throws ValidationException
     */
    private function validateOrderData(array $data): void
    {
        // Update validation based on actual Shipway API requirements
        $required = [
            'order_id',
            'products',
            'payment_type',
            'shipping_country',    // Required according to table
            'shipping_phone',      // Required according to table
            'shipping_zipcode',    // Required according to table
        ];

        $missing = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new ValidationException(
                'Missing required fields: ' . implode(', ', $missing),
                422,
                ['missing_fields' => $missing]
            );
        }

        // Validate products
        if (!isset($data['products']) || !is_array($data['products']) || empty($data['products'])) {
            throw new ValidationException('At least one product is required', 422);
        }

        // Validate payment type
        $validPaymentTypes = ['P', 'C']; // P = Prepaid, C = COD
        if (!in_array($data['payment_type'], $validPaymentTypes)) {
            throw new ValidationException(
                'Invalid payment_type. Must be one of: ' . implode(', ', $validPaymentTypes),
                422
            );
        }

        // Validate products structure
        foreach ($data['products'] as $index => $product) {
            if (!isset($product['product']) || empty($product['product'])) {
                throw new ValidationException("Product name is required for product at index {$index}", 422);
            }
            if (!isset($product['price']) || empty($product['price'])) {
                throw new ValidationException("Product price is required for product at index {$index}", 422);
            }
        }
    }

    /**
     * Generate AWB for an order
     * 
     * @param string $orderId The order ID
     * @return array API response with AWB details
     */
    public function generateAwb(string $orderId): array
    {
        return $this->post([], "/{$orderId}/generate-awb");
    }

    /**
     * Get shipping label for an order
     * 
     * @param string $orderId The order ID
     * @return array API response with label URL
     */
    public function getLabel(string $orderId): array
    {
        return parent::get("/{$orderId}/label");
    }

    /**
     * Get manifest for multiple orders
     * 
     * @param array $orderIds Array of order IDs
     * @return array API response with manifest URL
     */
    public function getManifest(array $orderIds): array
    {
        return $this->post(['order_ids' => $orderIds], '/manifest');
    }

    /**
     * Get order by AWB number
     * 
     * @param string $awbNumber AWB tracking number
     * @return array Order data
     */
    public function findByAwb(string $awbNumber): array
    {
        return parent::get("/awb/{$awbNumber}");
    }
}