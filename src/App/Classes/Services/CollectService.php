<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10\Collect;

class CollectService extends BaseService
{
    public $collect;
    public function __construct($channel_id) {
        $this->collect = new Collect();
        $this->collect->initializeSession([
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
                $collections = $this->collect::all($this->collect->session,[],$params);
                $callback($collections);
                $pageInfo = isset( $this->collect::$NEXT_PAGE_QUERY['page_info']) ? $this->collect::$NEXT_PAGE_QUERY['page_info'] : null;

            } while ($pageInfo);


    }

}
