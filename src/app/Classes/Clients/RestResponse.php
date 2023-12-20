<?php

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Clients;

class RestResponse extends HttpResponse
{
    /** @var PageInfo|null */
    private $pageInfo = null;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null,
        ?PageInfo $pageInfo = null
    ) {
        parent::__construct($status, $headers, $body, $version, $reason);
        $this->pageInfo = $pageInfo;
    }

    /**
     * @return \ShopifyConnector\App\Classes\Clients\PageInfo|null Pagination Information
     */
    public function getPageInfo(): ?PageInfo
    {
        return $this->pageInfo;
    }
}
