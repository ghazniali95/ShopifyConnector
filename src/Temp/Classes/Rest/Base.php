<?php

declare(strict_types=1);

namespace  Ghazniali95\ShopifyConnector\App\Classes\Rest;

use ReflectionClass;
use App\Models\Channel\ShopifyChannel;
use Doctrine\Inflector\InflectorFactory;
use Ghazniali95\ShopifyConnector\App\Classes\Context;
use Ghazniali95\ShopifyConnector\App\Classes\Auth\Session;
use Ghazniali95\ShopifyConnector\App\Classes\Clients\Http;
use Ghazniali95\ShopifyConnector\App\Classes\Clients\Rest;
use Ghazniali95\ShopifyConnector\App\Services\ShopifyConnector;
use Ghazniali95\ShopifyConnector\App\Classes\Clients\HttpHeaders;
use Ghazniali95\ShopifyConnector\App\Classes\Clients\HttpResponse;
use Ghazniali95\ShopifyConnector\App\Classes\Clients\RestResponse;
use Ghazniali95\ShopifyConnector\App\Classes\Auth\FileSessionStorage;
use Ghazniali95\ShopifyConnector\App\Exception\RestResourceException;
use Ghazniali95\ShopifyConnector\App\Exception\MissingArgumentException;
use Ghazniali95\ShopifyConnector\App\Exception\RestResourceRequestException;

// When upgrading to PHP 8.2, consider using the AllowDynamicProperties attribute
// https://stitcher.io/blog/deprecated-dynamic-properties-in-php-82#a-better-alternative
abstract class Base extends ShopifyConnector
{
    public static string $API_VERSION;
    public static ?array $NEXT_PAGE_QUERY = null;
    public static ?array $PREV_PAGE_QUERY = null;

    /** @var Base[] */
    protected static array $HAS_ONE = [];

    /** @var Base[] */
    protected static array $HAS_MANY = [];

    /** @var array[] */
    protected static array $PATHS = [];

    protected static string $PRIMARY_KEY = "id";
    protected static ?string $CUSTOM_PREFIX = null;

    /** @var string[] */
    protected static array $READ_ONLY_ATTRIBUTES = [];

    private array $originalState;
    private array $setProps;
    private array $lastApiCallTimes = []; // Timestamps of the last API calls for rate limiting.
    public Session $session;
    public $organization_id;
    public $channel_id;

    public function __construct(?Session $session = null, array $fromData = null , $data = [])
    {
        $this->organization_id = isset($data['organization_id']) ? $data['organization_id'] : null;
        $this->channel_id = isset($data['channel_id']) ? $data['channel_id'] : null;
        if(!is_null($this->channel_id)){
            $this->session = $this->initializeSession($data = []);
        if (Context::$API_VERSION !== static::$API_VERSION) {
            $contextVersion = Context::$API_VERSION;
            $thisVersion = static::$API_VERSION;
            throw new RestResourceException(
                "Current Context::\$API_VERSION '$contextVersion' does not match resource version '$thisVersion'",
            );
        }
        }else{
            if(!is_null($session)){
                $this->session = $session;
            }

        }

        $this->originalState = [];
        $this->setProps = [];


        if (!empty($fromData)) {
            self::setInstanceData($this, $fromData);
        }
    }

    public function save($updateObject = false): void
    {
        $data = self::dataDiff($this->toArray(true), $this->originalState);

        $method = !empty($data[static::$PRIMARY_KEY]) ? "put" : "post";

        $saveBody = [static::getJsonBodyName() => $data];
        $response = self::request($method, $method, $this->session, [], [], $saveBody, $this);

        if ($updateObject) {
            $body = $response->getDecodedBody();

            self::createInstance($body[$this->getJsonBodyName()], $this->session, $this);
        }
    }

    public function saveAndUpdate(): void
    {
        $this->save(true);
    }

    public function __get(string $name)
    {
        return array_key_exists($name, $this->setProps) ? $this->setProps[$name] : null;
    }

    public function __set(string $name, $value): void
    {
        $this->setProperty($name, $value);
    }

    public static function getNextPageInfo()
    {
        return static::$NEXT_PAGE_QUERY;
    }

    public static function getPreviousPageInfo()
    {
        return static::$PREV_PAGE_QUERY;
    }

    public function toArray($saving = false): array
    {
        $data = [];

        foreach ($this->getProperties() as $prop) {
            if ($saving && in_array($prop, static::$READ_ONLY_ATTRIBUTES)) {
                continue;
            }

            $includeProp = !empty($this->$prop) || array_key_exists($prop, $this->setProps);
            if (self::isHasManyAttribute($prop)) {
                if ($includeProp) {
                    $data[$prop] = [];
                    /** @var self $assoc */
                    foreach ($this->$prop as $assoc) {
                        array_push($data[$prop], $this->subAttributeToArray($assoc, $saving));
                    }
                }
            } elseif (self::isHasOneAttribute($prop)) {
                if ($includeProp) {
                    $data[$prop] = $this->subAttributeToArray($this->$prop, $saving);
                }
            } elseif ($includeProp) {
                $data[$prop] = $this->$prop;
            }
        }

        return $data;
    }

    protected static function getJsonBodyName(): string
    {
        $className = preg_replace("/^([A-z_0-9]+\\\)*([A-z_]+)/", "$2", static::class);
        return strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $className));
    }

    protected static function getJsonResponseBodyNames(): array
    {
        $className = preg_replace("/^([A-z_0-9]+\\\)*([A-z_]+)/", "$2", static::class);
        return [strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $className))];
    }

    /**
     * @param string[]|int[] $ids
     *
     * @return static[]
     */
    protected static function baseFind(Session $session, array $ids = [], array $params = []): array
    {

        $response = self::request("get", "get", $session, $ids, $params);

        static::$NEXT_PAGE_QUERY = static::$PREV_PAGE_QUERY = null;
        $pageInfo = $response->getPageInfo();
        if ($pageInfo) {
            static::$NEXT_PAGE_QUERY = $pageInfo->hasNextPage() ? $pageInfo->getNextPageQuery() : null;
            static::$PREV_PAGE_QUERY = $pageInfo->hasPreviousPage() ? $pageInfo->getPreviousPageQuery() : null;
        }

        return static::createInstancesFromResponse($response, $session);
    }

    /**
     * @param static $entity
     */
    protected static function request(
        string $httpMethod,
        string $operation,
        Session $session,
        array $ids = [],
        array $params = [],
        array $body = [],
        self $entity = null
    ): RestResponse {
        $path = static::getPath($httpMethod, $operation, $ids, $entity);

        $client = new Rest($session->getShop(), $session->getAccessToken());

        $params = array_filter($params);

        switch ($httpMethod) {
            case "get":
                $response = $client->get(
                    path: $path,
                    query: $params,
                );
                break;
            case "post":
                $response = $client->post(
                    path: $path,
                    body: $body,
                    query: $params,
                );
                break;
            case "put":
                $response = $client->put(
                    path: $path,
                    body: $body,
                    query: $params,
                );
                break;
            case "delete":
                $response = $client->delete(
                    path: $path,
                    query: $params,
                );
                break;
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode >= 300) {
            $message = "REST request failed";

            $body = $response->getDecodedBody();
            if (!empty($body["errors"])) {
                $bodyErrors = json_encode($body["errors"]);
                $message .= ": {$bodyErrors}";
            }

            throw new RestResourceRequestException($message, $statusCode);
        }
        // dump($response);
        return $response;
    }

    /**
     * @param string[]|int[] $ids
     */
    private static function getPath(
        string $httpMethod,
        string $operation,
        array $ids,
        self $entity = null
    ): ?string {
        $match = null;

        $maxIds = -1;
        foreach (static::$PATHS as $path) {
            if ($httpMethod !== $path["http_method"] || $operation !== $path["operation"]) {
                continue;
            }

            $urlIds = $ids;
            foreach ($path["ids"] as $id) {
                if ((!array_key_exists($id, $ids) || $ids[$id] === null) && $entity && $entity->$id) {
                    $urlIds[$id] = $entity->$id;
                }
            }
            $urlIds = array_filter($urlIds);

            if (!empty(array_diff($path["ids"], array_keys($urlIds))) || count($path["ids"]) <= $maxIds) {
                continue;
            }

            $maxIds = count($path["ids"]);
            $match = preg_replace_callback(
                '/(<([^>]+)>)/',
                function ($matches) use ($urlIds) {
                    return $urlIds[$matches[2]];
                },
                $path["path"]
            );
        }

        if (empty($match)) {
            throw new RestResourceException("Could not find a path for request");
        }

        if (static::$CUSTOM_PREFIX) {
            $match = preg_replace("/^\/?/", "", static::$CUSTOM_PREFIX) . "/$match";
        }
        return $match;
    }

    /**
     * @return static[]
     */
    private static function createInstancesFromResponse(RestResponse $response, Session $session): array
    {
        $objects = [];

        $body = $response->getDecodedBody();
        $classNames = static::getJsonResponseBodyNames();

        foreach ($classNames as $className) {
            $pluralClass = self::pluralize($className);
            if (!empty($body)) {
                if (array_key_exists($pluralClass, $body)) {
                    foreach ($body[$pluralClass] as $entry) {
                        array_push($objects, self::createInstance($entry, $session));
                    }
                } elseif (array_key_exists($className, $body) && array_key_exists(0, $body[$className])) {
                    foreach ($body[$className] as $entry) {
                        array_push($objects, self::createInstance($entry, $session));
                    }
                } elseif (array_key_exists($className, $body)) {
                    array_push($objects, self::createInstance($body[$className], $session));
                }
            }
        }

        return $objects;
    }

    /**
     * @return static
     */
    private static function createInstance(array $data, Session $session, &$instance = null)
    {
        $instance = $instance ?: new static($session);

        if (!empty($data)) {

            self::setInstanceData($instance, $data);
        }

        return $instance;
    }

    private static function isHasManyAttribute(string $property): bool
    {
        return array_key_exists($property, static::$HAS_MANY);
    }

    private static function isHasOneAttribute(string $property): bool
    {
        return array_key_exists($property, static::$HAS_ONE);
    }

    private static function pluralize(string $str): string
    {
        $inflector = InflectorFactory::create()->build();
        return $inflector->pluralize($str);
    }

    private static function setInstanceData(self &$instance, array $data): void
    {
        $instance->originalState = [];

        foreach ($data as $prop => $value) {
            if (self::isHasManyAttribute($prop)) {
                $attrList = [];
                if (!empty($value)) {
                    foreach ($value as $elementData) {
                        array_push(
                            $attrList,
                            static::$HAS_MANY[$prop]::createInstance($elementData, $instance->session)
                        );
                    }
                }

                $instance->setProperty($prop, $attrList);
            } elseif (self::isHasOneAttribute($prop)) {
                if (!empty($value)) {
                    $valueArray = is_array($value) ? $value : [$prop => $value];

                    $instance->setProperty(
                        $prop,
                        static::$HAS_ONE[$prop]::createInstance($valueArray, $instance->session)
                    );
                }
            } else {
                $instance->setProperty($prop, $value);
                $instance->originalState[$prop] = $value;
            }
        }
    }

    private static function dataDiff(array $data1, array $data2): array
    {
        $diff = array();

        foreach ($data1 as $key1 => $value1) {
            if (array_key_exists($key1, $data2)) {
                if (is_array($value1)) {
                    $recursiveDiff = self::dataDiff($value1, $data2[$key1]);
                    if (count($recursiveDiff)) {
                        $diff[$key1] = $recursiveDiff;
                    }
                } else {
                    if ($value1 != $data2[$key1]) {
                        $diff[$key1] = $value1;
                    }
                }
            } else {
                $diff[$key1] = $value1;
            }
        }
        return $diff;
    }

    private function setProperty(string $name, $value): void
    {
        $this->$name = $value;
        $this->setProps[$name] = $value;
    }

    private function getProperties(): array
    {
        $reflection = new ReflectionClass(static::class);
        $docBlock = $reflection->getDocComment();
        $lines = explode("\n", $docBlock);

        $props = [];
        foreach ($lines as $line) {
            preg_match("/[\s\*]+@property\s+[^\s]+\s+\\$(.*)/", $line, $matches);
            if (empty($matches)) {
                continue;
            }

            $props[] = $matches[1];
        }

        return array_unique(array_merge($props, array_keys($this->setProps)));
    }

    /**
     * @param array|null|Base $attribute
     * @return array|null
     */
    private function subAttributeToArray($attribute, bool $saving)
    {
        if (is_array($attribute)) {
            $subAttribute = static::createInstance($attribute, $this->session);
            $retVal = $subAttribute->toArray($saving);
        } elseif (empty($attribute)) {
            $retVal = $attribute;
        } else {
            $retVal = $attribute->toArray($saving);
        }

        return $retVal;
    }

        /**
     * Throttles API calls to comply with Shopify's rate limit.
     * Introduces a delay if necessary to avoid exceeding the limit.
     */
    public function throttleApiCall(): void
    {
        $currentTime = microtime(true);
        $this->lastApiCallTimes[] = $currentTime;

        // Delay execution if making requests too quickly
        if (count($this->lastApiCallTimes) > 2) {
            $timeDifference = $currentTime - $this->lastApiCallTimes[0];
            if ($timeDifference < 1) {
                usleep((1 - $timeDifference) * 1000000);
            }
            array_shift($this->lastApiCallTimes);
        }
    }

    /**
     * Initializes the HTTP client for Shopify API requests.
     * Configures base URI and headers including authentication details.
     */
    public function initializeSession($data): Session
    {
        $this->organization_id = isset($data['organization_id']) ? $data['organization_id'] : null;
        $this->channel_id = isset($data['channel_id']) ? $data['channel_id'] : null;
        Context::initialize(
            env("SHOPIFY_API_KEY","3bda7be4f9789a1f43d84e3d8f13fc22"),
            env("SHOPIFY_SHARED_SECRET","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"),
             env("SHOPIFY_SCOPES","write_products,read_inventory,write_inventory,read_locations"),
             env("APP_URL","http://localhost:8000/"),
             new FileSessionStorage(storage_path('shopify/sessions/')),
                 '2023-10',
                 true,
                 false,
                 null,
                 '',
                 null,
                [ $this->channelDetail()->shop]

             );

            $this->session = new Session(
                id: $this->channelDetail()->shop_id,
                shop: $this->channelDetail()->shop,
                isOnline: true,
                state:'NA'
            );
            $this->session->setAccessToken($this->channelDetail()->access_token);
            $this->session->setScope(env("SHOPIFY_SCOPES","write_products,read_inventory,write_inventory,read_locations"));
            return $this->session;
    }

    public function channelDetail()
    {
        return ShopifyChannel::where("channel_id",$this->channel_id)->first();
    }

        /**
     * Sends a GraphQL query to this client's domain.
     *
     * @param string|array   $data         Query to be posted to endpoint
     * @param array          $query        Parameters on a query to be added to the URL
     * @param array          $extraHeaders Any extra headers to send along with the request
     * @param int|null       $tries        How many times to attempt the request
     *
     * @return HttpResponse
     * @throws \ShopifyConnector\App\Classes\Exception\HttpRequestException
     * @throws \ShopifyConnector\App\Classes\Exception\MissingArgumentException
     */
    public function query(
        $data,
        array $query = [],
        array $extraHeaders = [],
        ?int $tries = null
    ): HttpResponse {
        if (empty($data)) {
            throw new MissingArgumentException('Query missing');
        }
        list($accessTokenHeader, $accessToken) = $this->getAccessTokenHeader();
        $extraHeaders[$accessTokenHeader] = $accessToken;

        if (is_array($data)) {
            $dataType = Http::DATA_TYPE_JSON;
            $data = json_encode($data);
        } else {
            $dataType = Http::DATA_TYPE_GRAPHQL;
        }
        $this->client = new Http($this->session->getShop());
        return $this->client->post(
            $this->getApiPath(),
            $data,
            $extraHeaders,
            $query,
            $tries,
            $dataType,
        );
    }

    /**
     * Proxy string query to this client's domain.
     *
     * @param string   $data         Query to be posted to endpoint
     * @param array    $extraHeaders Any extra headers to send along with the request
     * @param int|null $tries        How many times to attempt the request
     *
     * @return \ShopifyConnector\App\Classes\Clients\HttpResponse
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \ShopifyConnector\App\Classes\Exception\MissingArgumentException
     * @throws \ShopifyConnector\App\Classes\Exception\UninitializedContextException
     */
    public function proxy(
        string $data,
        array $extraHeaders = [],
        ?int $tries = null
    ): HttpResponse {
        if (empty($data)) {
            throw new MissingArgumentException('Query missing');
        }

        list($accessTokenHeader, $accessToken) = $this->getAccessTokenHeader();
        $extraHeaders[$accessTokenHeader] = $accessToken;
        $this->client = new Http($this->session->getShop());
        return $this->client->post(
            $this->getApiPath(),
            $data,
            $extraHeaders,
            [],
            $tries,
            Http::DATA_TYPE_JSON,
        );
    }

    /**
     * Fetches the URL path to be used for API requests.
     *
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'admin/api/' . Context::$API_VERSION . '/graphql.json';
    }

    /**
     * Fetches the access token header and value to be used for API requests.
     *
     * @return array [$accessTokenHeader, $accessToken]
     */
    protected function getAccessTokenHeader(): array
    {
        $accessToken = Context::$IS_PRIVATE_APP ? Context::$API_SECRET_KEY : $this->session->getAccessToken();
        return [HttpHeaders::X_SHOPIFY_ACCESS_TOKEN, $accessToken];
    }

}
