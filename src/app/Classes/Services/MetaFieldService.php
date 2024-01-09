<?php

/***********************************************************************************************************************
* This file is auto-generated. If you have an issue, please create a GitHub issue.                                     *
***********************************************************************************************************************/

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Ghazniali95\ShopifyConnector\App\Classes\Rest\Admin2023_10\Metafield;

class MetaFieldService extends BaseService
{
    public $metaField;
    public function __construct($channel_id) {
        $this->metaField = new Metafield();
        $this->metaField->initializeSession([
            "channel_id" => $channel_id,
        ]);
        parent::__construct($channel_id);
    }

    public function getAll(callable $callback ,$data = []): void {
            $pageInfo = null;
            do {
                $metaFields = $this->metaField::all($this->metaField->session,[],['limit' => isset($data['limit']) ? $data['limit'] : 250,'page_info' => $pageInfo,"metafield" => ["owner_id" => isset($data['owner_id']) ?  $data['owner_id'] : null , "owner_resource" =>  isset($data['owner_resource']) ? $data['owner_resource'] : 'product']]);
                $callback($metaFields);
                $pageInfo = isset( $this->metaField::$NEXT_PAGE_QUERY['page_info']) ? $this->metaField::$NEXT_PAGE_QUERY['page_info'] : null;
            } while ($pageInfo);


    }

}
