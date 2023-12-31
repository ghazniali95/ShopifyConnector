<?php

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Webhooks;

/**
 * Contains a list of known webhook topics.
 *
 * For an up-to-date list of topics, you can visit
 * https://shopify.dev/docs/api/admin-graphql/latest/enums/webhooksubscriptiontopic
 */
final class Topics
{
    public const APP_PURCHASES_ONE_TIME_UPDATE = 'APP_PURCHASES_ONE_TIME_UPDATE';
    public const APP_SUBSCRIPTIONS_UPDATE = 'APP_SUBSCRIPTIONS_UPDATE';
    public const APP_UNINSTALLED = 'APP_UNINSTALLED';
    public const ATTRIBUTED_SESSIONS_FIRST = 'ATTRIBUTED_SESSIONS_FIRST';
    public const ATTRIBUTED_SESSIONS_LAST = 'ATTRIBUTED_SESSIONS_LAST';
    public const ATTRIBUTION_RISK = 'ATTRIBUTION_RISK';
    public const CARTS_CREATE = 'CARTS_CREATE';
    public const CARTS_UPDATE = 'CARTS_UPDATE';
    public const CHANNELS_DELETE = 'CHANNELS_DELETE';
    public const CHECKOUTS_CREATE = 'CHECKOUTS_CREATE';
    public const CHECKOUTS_DELETE = 'CHECKOUTS_DELETE';
    public const CHECKOUTS_UPDATE = 'CHECKOUTS_UPDATE';
    public const COLLECTIONS_CREATE = 'COLLECTIONS_CREATE';
    public const COLLECTIONS_DELETE = 'COLLECTIONS_DELETE';
    public const COLLECTIONS_UPDATE = 'COLLECTIONS_UPDATE';
    public const COLLECTION_LISTINGS_ADD = 'COLLECTION_LISTINGS_ADD';
    public const COLLECTION_LISTINGS_REMOVE = 'COLLECTION_LISTINGS_REMOVE';
    public const COLLECTION_LISTINGS_UPDATE = 'COLLECTION_LISTINGS_UPDATE';
    public const COLLECTION_PUBLICATIONS_CREATE = 'COLLECTION_PUBLICATIONS_CREATE';
    public const COLLECTION_PUBLICATIONS_DELETE = 'COLLECTION_PUBLICATIONS_DELETE';
    public const COLLECTION_PUBLICATIONS_UPDATE = 'COLLECTION_PUBLICATIONS_UPDATE';
    public const CUSTOMERS_CREATE = 'CUSTOMERS_CREATE';
    public const CUSTOMERS_DELETE = 'CUSTOMERS_DELETE';
    public const CUSTOMERS_DISABLE = 'CUSTOMERS_DISABLE';
    public const CUSTOMERS_ENABLE = 'CUSTOMERS_ENABLE';
    public const CUSTOMERS_UPDATE = 'CUSTOMERS_UPDATE';
    public const CUSTOMER_GROUPS_CREATE = 'CUSTOMER_GROUPS_CREATE';
    public const CUSTOMER_GROUPS_DELETE = 'CUSTOMER_GROUPS_DELETE';
    public const CUSTOMER_GROUPS_UPDATE = 'CUSTOMER_GROUPS_UPDATE';
    public const CUSTOMER_PAYMENT_METHODS_CREATE = 'CUSTOMER_PAYMENT_METHODS_CREATE';
    public const CUSTOMER_PAYMENT_METHODS_REVOKE = 'CUSTOMER_PAYMENT_METHODS_REVOKE';
    public const CUSTOMER_PAYMENT_METHODS_UPDATE = 'CUSTOMER_PAYMENT_METHODS_UPDATE';
    public const DISPUTES_CREATE = 'DISPUTES_CREATE';
    public const DISPUTES_UPDATE = 'DISPUTES_UPDATE';
    public const DOMAINS_CREATE = 'DOMAINS_CREATE';
    public const DOMAINS_DESTROY = 'DOMAINS_DESTROY';
    public const DOMAINS_UPDATE = 'DOMAINS_UPDATE';
    public const DRAFT_ORDERS_CREATE = 'DRAFT_ORDERS_CREATE';
    public const DRAFT_ORDERS_DELETE = 'DRAFT_ORDERS_DELETE';
    public const DRAFT_ORDERS_UPDATE = 'DRAFT_ORDERS_UPDATE';
    public const FULFILLMENTS_CREATE = 'FULFILLMENTS_CREATE';
    public const FULFILLMENTS_UPDATE = 'FULFILLMENTS_UPDATE';
    public const FULFILLMENT_EVENTS_CREATE = 'FULFILLMENT_EVENTS_CREATE';
    public const FULFILLMENT_EVENTS_DELETE = 'FULFILLMENT_EVENTS_DELETE';
    public const INVENTORY_ITEMS_CREATE = 'INVENTORY_ITEMS_CREATE';
    public const INVENTORY_ITEMS_DELETE = 'INVENTORY_ITEMS_DELETE';
    public const INVENTORY_ITEMS_UPDATE = 'INVENTORY_ITEMS_UPDATE';
    public const INVENTORY_LEVELS_CONNECT = 'INVENTORY_LEVELS_CONNECT';
    public const INVENTORY_LEVELS_DISCONNECT = 'INVENTORY_LEVELS_DISCONNECT';
    public const INVENTORY_LEVELS_UPDATE = 'INVENTORY_LEVELS_UPDATE';
    public const LOCALES_CREATE = 'LOCALES_CREATE';
    public const LOCALES_UPDATE = 'LOCALES_UPDATE';
    public const LOCATIONS_CREATE = 'LOCATIONS_CREATE';
    public const LOCATIONS_DELETE = 'LOCATIONS_DELETE';
    public const LOCATIONS_UPDATE = 'LOCATIONS_UPDATE';
    public const ORDERS_CANCELLED = 'ORDERS_CANCELLED';
    public const ORDERS_CREATE = 'ORDERS_CREATE';
    public const ORDERS_DELETE = 'ORDERS_DELETE';
    public const ORDERS_EDITED = 'ORDERS_EDITED';
    public const ORDERS_FULFILLED = 'ORDERS_FULFILLED';
    public const ORDERS_PAID = 'ORDERS_PAID';
    public const ORDERS_PARTIALLY_FULFILLED = 'ORDERS_PARTIALLY_FULFILLED';
    public const ORDERS_UPDATED = 'ORDERS_UPDATED';
    public const ORDER_TRANSACTIONS_CREATE = 'ORDER_TRANSACTIONS_CREATE';
    public const PRODUCTS_CREATE = 'PRODUCTS_CREATE';
    public const PRODUCTS_DELETE = 'PRODUCTS_DELETE';
    public const PRODUCTS_UPDATE = 'PRODUCTS_UPDATE';
    public const PRODUCT_LISTINGS_ADD = 'PRODUCT_LISTINGS_ADD';
    public const PRODUCT_LISTINGS_REMOVE = 'PRODUCT_LISTINGS_REMOVE';
    public const PRODUCT_LISTINGS_UPDATE = 'PRODUCT_LISTINGS_UPDATE';
    public const PRODUCT_PUBLICATIONS_CREATE = 'PRODUCT_PUBLICATIONS_CREATE';
    public const PRODUCT_PUBLICATIONS_DELETE = 'PRODUCT_PUBLICATIONS_DELETE';
    public const PRODUCT_PUBLICATIONS_UPDATE = 'PRODUCT_PUBLICATIONS_UPDATE';
    public const PROFILES_CREATE = 'PROFILES_CREATE';
    public const PROFILES_DELETE = 'PROFILES_DELETE';
    public const PROFILES_UPDATE = 'PROFILES_UPDATE';
    public const REFUNDS_CREATE = 'REFUNDS_CREATE';
    public const SHIPPING_ADDRESSES_CREATE = 'SHIPPING_ADDRESSES_CREATE';
    public const SHIPPING_ADDRESSES_UPDATE = 'SHIPPING_ADDRESSES_UPDATE';
    public const SHOP_UPDATE = 'SHOP_UPDATE';
    public const SUBSCRIPTION_BILLING_ATTEMPTS_FAILURE = 'SUBSCRIPTION_BILLING_ATTEMPTS_FAILURE';
    public const SUBSCRIPTION_BILLING_ATTEMPTS_SUCCESS = 'SUBSCRIPTION_BILLING_ATTEMPTS_SUCCESS';
    public const SUBSCRIPTION_CONTRACTS_CREATE = 'SUBSCRIPTION_CONTRACTS_CREATE';
    public const SUBSCRIPTION_CONTRACTS_UPDATE = 'SUBSCRIPTION_CONTRACTS_UPDATE';
    public const TAX_SERVICES_CREATE = 'TAX_SERVICES_CREATE';
    public const TAX_SERVICES_UPDATE = 'TAX_SERVICES_UPDATE';
    public const TENDER_TRANSACTIONS_CREATE = 'TENDER_TRANSACTIONS_CREATE';
    public const THEMES_CREATE = 'THEMES_CREATE';
    public const THEMES_DELETE = 'THEMES_DELETE';
    public const THEMES_PUBLISH = 'THEMES_PUBLISH';
    public const THEMES_UPDATE = 'THEMES_UPDATE';
    public const VARIANTS_IN_STOCK = 'VARIANTS_IN_STOCK';
    public const VARIANTS_OUT_OF_STOCK = 'VARIANTS_OUT_OF_STOCK';
}
