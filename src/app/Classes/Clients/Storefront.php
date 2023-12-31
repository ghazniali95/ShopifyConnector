<?php

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Clients;

use Ghazniali95\ShopifyConnector\App\Classes\Context;

final class Storefront extends Graphql
{
    /**
     * @inheritDoc
     */
    protected function getApiPath(): string
    {
        return 'api/' . Context::$API_VERSION . '/graphql.json';
    }

    /**
     * @inheritDoc
     */
    protected function getAccessTokenHeader(): array
    {
        $accessToken = Context::$IS_PRIVATE_APP ?
            (Context::$PRIVATE_APP_STOREFRONT_ACCESS_TOKEN ?: $this->token) :
            $this->token;
        return [HttpHeaders::X_SHOPIFY_STOREFRONT_ACCESS_TOKEN, $accessToken];
    }
}
