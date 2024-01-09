<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10\Product;

class ProductService extends BaseService
{
    public $product;
    public function __construct($channel_id) {
        $this->product = new Product();
        $this->product->initializeSession([
            "channel_id" => $channel_id,
        ]);
        parent::__construct($channel_id);
    }

    public function getAll(callable $callback , $data = []) {
        $pageInfo = null;
        do {
            $products = $this->product::all($this->product->session,[],['limit' => isset($data['limit']) ? $data['limit'] : 250,'page_info' => $pageInfo]);
            $callback($products);
            $pageInfo = isset( $this->product::$NEXT_PAGE_QUERY['page_info']) ? $this->product::$NEXT_PAGE_QUERY['page_info'] : null;

        } while ($pageInfo);

    }

}
