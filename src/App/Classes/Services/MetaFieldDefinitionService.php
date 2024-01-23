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

    public function getAll($params = [])
    {
            $response = $this->metaField->query(["query" => $this->getQuery($params)]);
            return $response->getDecodedBody();
    }

    private function getQuery($params): string
    {
        $type = strtoupper($params['ownerType']);
        return  <<<QUERY
                query {
                    metafieldDefinitions(first: {$params['limit']}, ownerType: {$type}) {
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
