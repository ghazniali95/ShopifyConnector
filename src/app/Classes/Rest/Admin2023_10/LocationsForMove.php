<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace ShopifyConnector\App\Classes\Rest\Admin2023_10;

use ShopifyConnector\App\Classes\Auth\Session;
use ShopifyConnector\App\Classes\Rest\Base;

/**
 * @property array[]|null $locations_for_move
 */
class LocationsForMove extends Base
{
    public static string $API_VERSION = "2023-10";
    protected static array $HAS_ONE = [];
    protected static array $HAS_MANY = [];
    protected static array $PATHS = [
        ["http_method" => "get", "operation" => "get", "ids" => ["fulfillment_order_id"], "path" => "fulfillment_orders/<fulfillment_order_id>/locations_for_move.json"]
    ];

    /**
     * @param Session $session
     * @param array $urlIds Allowed indexes:
     *     fulfillment_order_id
     * @param mixed[] $params
     *
     * @return LocationsForMove[]
     */
    public static function all(
        Session $session,
        array $urlIds = [],
        array $params = []
    ): array {
        return parent::baseFind(
            $session,
            $urlIds,
            $params,
        );
    }

}
