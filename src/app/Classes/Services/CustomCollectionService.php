<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10\CustomCollection;

class CustomCollectionService extends BaseService
{
    public $collectionList;
    public function __construct($channel_id) {
        $this->collectionList = new CustomCollection();
        $this->collectionList->initializeSession([
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
                $collectionListings = $this->collectionList::all($this->collectionList->session,[],$params);
                $callback($collectionListings);
                $pageInfo = isset( $this->collectionList::$NEXT_PAGE_QUERY['page_info']) ? $this->collectionList::$NEXT_PAGE_QUERY['page_info'] : null;
            } while ($pageInfo);
    }

}
