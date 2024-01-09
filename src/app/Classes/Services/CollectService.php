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

    public function getAll(callable $callback , $data = []): void {
            $pageInfo = null;
            do {
                $collections = $this->collect::all($this->collect->session,[],['limit' => isset($data['limit']) ? $data['limit'] : 250,'page_info' => $pageInfo]);
                $callback($collections);
                $pageInfo = isset( $this->collect::$NEXT_PAGE_QUERY['page_info']) ? $this->collect::$NEXT_PAGE_QUERY['page_info'] : null;

            } while ($pageInfo);


    }

}
