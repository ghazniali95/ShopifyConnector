<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10\InventoryLevel;

class InventoryLevelService extends BaseService
{
    public $inventoryLevel;
    public function __construct($channel_id) {
        $this->inventoryLevel = new InventoryLevel();
        $this->inventoryLevel->initializeSession([
            "channel_id" => $channel_id,
        ]);
        parent::__construct($channel_id);
    }

    public function getAll(callable $callback , $params = []): void {
            $pageInfo = null;
            do {
                if($pageInfo != null){
                    $params = array_merge($params,["page_info" => $pageInfo]);
                    unset($params['location_ids']);
                }
                $inventories = $this->inventoryLevel::all($this->inventoryLevel->session,[],$params);
                $callback($inventories);
                $pageInfo = isset( $this->inventoryLevel::$NEXT_PAGE_QUERY['page_info']) ? $this->inventoryLevel::$NEXT_PAGE_QUERY['page_info'] : null;

            } while ($pageInfo);


    }

}
