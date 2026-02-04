# Shipway PHP SDK

A clean, structured PHP SDK for interacting with the **Shipway API**, providing support for order management, courier services, rate calculation, tracking, and manifests.

This SDK is designed as a **Composer package**, following PSR-4 autoloading and a clear separation of concerns (Config → Client → Resources → Models).

---

## 📦 Installation

Install via Composer:

```bash
composer require mimicak/shipway-php-sdk
````

Or include it manually by adding it to your `composer.json`.

---

## 🔧 Requirements

* PHP >= 7.4
* Composer
* cURL enabled

---

## 🧠 Architecture Overview

The SDK is structured into logical layers:

```
src/
├── Client          # HTTP & response handling
├── Config          # API configuration & endpoints
├── Exceptions      # Domain-specific exceptions
├── Models
│   ├── Request     # Request DTOs
│   ├── Response    # Response DTOs
│   └── Core models (Order, Address, Product, etc.)
├── Resources       # API feature modules
└── Shipway.php     # SDK entry point
```

---

## 🚀 Quick Start

### 1️⃣ Create Configuration

```php
use Shipway\Config\Configuration;

$config = new Configuration([
    'api_key' => 'YOUR_API_KEY',
    'base_url' => 'https://app.shipway.com/api'
]);
```

---

### 2️⃣ Initialize SDK

```php
use Shipway\Shipway;

$shipway = new Shipway($config);
```

The `Shipway` class is the **main entry point**.
All API interactions go through resource accessors.

---

## 📦 Resources & Usage

### 🧾 Orders

```php
$orders = $shipway->orders();
```

Supported operations:

* Create order
* Fetch order details
* Fetch order list
* Cancel shipment
* Generate manifest

Example:

```php
use Shipway\Models\Request\ShipmentBooking\GetOrdersRequest;

$request = new GetOrdersRequest([
    'from_date' => '2025-01-01',
    'to_date'   => '2025-01-31'
]);

$response = $shipway->orders()->getOrders($request);
```

---

### 🚚 Courier Services

```php
$courier = $shipway->courier();
```

Supported operations:

* Get courier list
* Rate calculation
* Pincode serviceability
* Shipment tracking

Example – Rate Calculation:

```php
use Shipway\Models\Request\Carriers\GetCarrierRates;

$request = new GetCarrierRates([
    'pickup_pincode'   => '560001',
    'delivery_pincode' => '110001',
    'weight'           => 1.5,
    'cod'              => true
]);

$response = $shipway->courier()->getRates($request);
```

---

### 🏭 Warehouse

```php
$warehouse = $shipway->warehouse();
```

Handles warehouse-related Shipway APIs such as listing or configuration (based on API availability).

---

## 📚 Models

### Core Models

* `Order`
* `OrderListItem`
* `Address`
* `Product`
* `ShipmentStatusScan`

### Request Models

Located in:

```
src/Models/Request/
```

These classes encapsulate API request payloads and prevent passing raw arrays.

### Response Models

Located in:

```
src/Models/Response/
```

These map API responses into typed PHP objects.

---

## ⚠️ Exception Handling

All exceptions extend `Shipway\Exceptions\ShipwayException`.

Possible exceptions include:

* `AuthenticationException`
* `ValidationException`
* `RateLimitException`
* `NetworkException`
* `ConfigurationException`
* `ApiException`
* `WebhookException`

Example:

```php
try {
    $shipway->orders()->getOrders($request);
} catch (\Shipway\Exceptions\ShipwayException $e) {
    echo $e->getMessage();
}
```

---

## 🔐 Configuration Errors

If the SDK is misconfigured (missing API key, invalid base URL), a `ConfigurationException` is thrown during initialization or request execution.

---

## 🧪 Examples

The `examples/` directory is reserved for future usage samples and integration demos.

---

## 🧩 Extensibility

* All API modules extend `AbstractResource`
* HTTP logic is centralized in `HttpClient`
* Response parsing is handled by `ResponseHandler`
* Exception mapping is centralized in `ExceptionFactory`

This makes the SDK easy to extend without breaking existing functionality.

---

## 📄 License

MIT License.

---

## 🤝 Contributing

Pull requests are welcome.
Please ensure:

* PSR-12 compliance
* Typed models for requests/responses
* No breaking changes without version bumps

---

## 📬 Support

For issues, feature requests, or API changes, open an issue on the repository.
