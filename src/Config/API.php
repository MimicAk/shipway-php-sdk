<?php

namespace MimicAk\ShipwayPhpSdk\Config;

/**
 * API configuration constants
 */
class API
{
    public const USER_AGENT = 'MimicAk-Shipway-PHP-SDK/1.0';

    // SHIPWAY API SLUGS

    public const PUSH_ORDER = '/v2orders';
    public const GET_ORDER = '/getorders';

    public const MANIFEST = '/Createmanifest';

    public const ONHOLD_ORDER = '/Onholdorders';
    public const CANCEL_ORDER = '/Cancelorders';

    public const CANCEL_SHIPMENT = '/Cancel';


    // Carrier related endpoints
    public const GET_CARRIERS = '/getcarrier';

    public const PINCODE_SERVICEABILITY = '/pincodeserviceable';

    public const SHIPWAY_CARRIER_RATES = '/getshipwaycarrierrates';


    public const TRACKING = '/tracking';

    public const TRACKING_BASE_URL = 'https://app.shipway.com/api' . self::TRACKING;
}