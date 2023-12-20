<?php

namespace  Ghazniali95\ShopifyConnector\App\Classes\Webhooks\Delivery;

use Ghazniali95\ShopifyConnector\App\Classes\Context;
use Ghazniali95\ShopifyConnector\App\Classes\Exception\InvalidArgumentException;
use Ghazniali95\ShopifyConnector\App\Classes\Utils;
use Ghazniali95\ShopifyConnector\App\Classes\Webhooks\DeliveryMethod;

class PubSub extends DeliveryMethod
{
    /**
     * @throws \ShopifyConnector\App\Classes\Exception\InvalidArgumentException
     */
    public function __construct()
    {
        if (!Utils::isApiVersionCompatible('2021-07')) {
            throw new InvalidArgumentException(
                "PubSub webhooks are not supported in API version " . Context::$API_VERSION
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getCallbackAddress(string $path): string
    {
        return $path;
    }

    /**
     * @inheritDoc
     */
    public function parseCheckQueryResult(array $body): array
    {
        $edges = $body['data']['webhookSubscriptions']['edges'] ?? [];

        $webhookId = null;
        $currentAddress = null;
        if (count($edges ?? [])) {
            $node = $edges[0]['node'];

            $webhookId = (string)$node['id'];
            $currentAddress = "pubsub://" . $node['endpoint']['pubSubProject'] . ":" . $node['endpoint']['pubSubTopic'];
        }
        return [$webhookId, $currentAddress];
    }

    /**
     * @inheritDoc
     */
    public function buildCheckQuery(string $topic): string
    {
        return <<<QUERY
            {
                webhookSubscriptions(first: 1, topics: $topic) {
                    edges {
                        node {
                            id
                            endpoint {
                                __typename
                                ... on WebhookPubSubEndpoint {
                                    pubSubProject
                                    pubSubTopic
                                }
                            }
                        }
                    }
                }
            }
            QUERY;
    }

    /**
     * @inheritDoc
     */
    protected function getMutationName(?string $webhookId): string
    {
        return $webhookId ? "pubSubWebhookSubscriptionUpdate" : "pubSubWebhookSubscriptionCreate";
    }

    /**
     * @inheritDoc
     */
    protected function queryEndpoint(string $address): string
    {
        $addressWithoutProtocol = explode("//", $address);
        list($project, $topic) = explode(":", $addressWithoutProtocol[1]);
        return "{pubSubProject: \"$project\", pubSubTopic: \"$topic\"}";
    }
}
