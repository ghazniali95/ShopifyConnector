<?php

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Exception;

use Ghazniali95\ShopifyConnector\App\Exception\ShopifyException;

class FeatureDeprecatedException extends ShopifyException
{
    /**
     * @param string $version The version om which a feature was deprecated
     */
    public function __construct(string $version)
    {
        parent::__construct(sprintf('Feature was deprecated in version %s', $version));
    }
}
