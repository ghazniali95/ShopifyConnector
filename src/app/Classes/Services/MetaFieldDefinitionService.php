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

class MetaFieldDefinitionService extends BaseService
{
    public $metaField;
    public function __construct($channel_id)
    {
        $this->metaField = new Metafield();
        $this->metaField->initializeSession([
            "channel_id" => $channel_id,
        ]);
        parent::__construct($channel_id);
    }
 
    public function getAll($data = [])
    { 
            $response = $this->metaField->query(["query" => $this->getQuery(isset($data['limit']) ? $data['limit'] : 250, isset($data['ownerType']) ? $data['ownerType'] : 'PRODUCT')]);
            return $response->getDecodedBody(); 
    }

    private function getQuery($limit = 250, $type = 'PRODUCT'): string
    {
        $type = strtoupper($type);
        return  <<<QUERY
                query {
                    metafieldDefinitions(first: {$limit}, ownerType: {$type}) {
                    edges {
                        node {
                            id
                            name
                            namespace
                            key
                            description
                            type {
                                name
                            }
                            validations {
                                    name
                                    type
                                    value
                                }
                            }
                        }
                    }
                }
           QUERY;
    }
}
