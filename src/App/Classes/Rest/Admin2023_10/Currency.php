<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10;

use Ghazniali95\ShopifyConnector\App\Classes\Auth\Session;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Base;

/**
 * @property string|null $currency
 * @property string|null $rate_updated_at
 */
class Currency extends Base
{
    public static string $API_VERSION = "2023-10";
    protected static array $HAS_ONE = [];
    protected static array $HAS_MANY = [];
    protected static array $PATHS = [
        ["http_method" => "get", "operation" => "get", "ids" => [], "path" => "currencies.json"]
    ];

    /**
     * @param Session $session
     * @param array $urlIds
     * @param mixed[] $params
     *
     * @return Currency[]
     */
    public static function all(
        Session $session,
        array $urlIds = [],
        array $params = []
    ): array {
        return parent::baseFind(
            $session,
            [],
            $params,
        );
    }

}