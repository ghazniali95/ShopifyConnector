<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10\Location;

class LocationService extends BaseService
{
    public $location;
    public function __construct($channel_id) {
        $this->location = new Location();
        $this->location->initializeSession([
            "channel_id" => $channel_id,
        ]);
        parent::__construct($channel_id);
    }

    public function getAll(callable $callback , $params = []): void {
            $pageInfo = null;
            do {
                if($pageInfo != null){
                    $params = array_merge($params,["page_info" => $pageInfo]);
                }
                $locations = $this->location::all($this->location->session,[],$params);
                $callback($locations);
                $pageInfo = isset( $this->location::$NEXT_PAGE_QUERY['page_info']) ? $this->location::$NEXT_PAGE_QUERY['page_info'] : null;

            } while ($pageInfo);


    }

}
