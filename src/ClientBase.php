<?php
/**
 * @author xp
 */
namespace FeishuBot;

use FeishuBot\Exception\FeishuClientResponseException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\StreamInterface;

class ClientBase
{
    private const HOSTS = [
        'GLOBAL' => [
            'ONLINE' => [
                'API' => 'open.larksuite.com',
                'APPROVAL' => 'www.larksuite.com',
            ],
            'STAGING' => [
                'API' => 'open.larksuite-staging.com',
                'APPROVAL' => 'www.larksuite-staging.com',
            ],
        ],
        'CN' => [
            'ONLINE' => [
                'API' => 'open.feishu.cn',
                'APPROVAL' => 'www.feishu.cn',
            ],
            'STAGING' => [
                'API' => 'open.feishu-staging.cn',
                'APPROVAL' => 'www.feishu-staging.cn',
            ],
        ],
    ];

    private string $hostMode;

    protected Config $config;

    private TokenStorage $tokenStorage;

    private HttpClient $client;

    public function __construct(Config $config, string $hostMode = 'GLOBAL.ONLINE') {
        $this->config = $config;
        $this->hostMode = $hostMode;

        $this->tokenStorage = new TokenStorage();

        $this->client = new HttpClient([
            'timeout' => 10.0,  // by sec
        ]);
    }

    public function getConfig() : Config {
        return $this->config;
    }

    public function getTokenStorage() : TokenStorage {
        return $this->tokenStorage;
    }

    private function getHost(string $suffix) : string {
        $host = self::HOSTS;
        $dirs = explode('.', strtoupper($this->hostMode . '.' . $suffix));
        foreach ($dirs as $dir) {
            $host = $host[$dir];
        }
        /** @var string $host */
        return $host;
    }

    private function joinURL(string $path, string $provider = 'API') : string {
        return 'https://' . $this->getHost($provider) . $path;
    }

    protected static function genQuery(array $base, ?array $optionals = null) : array {
        if (is_array($optionals)) {
            $queries = $base;
            foreach ($optionals as $key => $value) {
                if (!is_null($value)) {
                    $queries[$key] = $value;
                }
            }
            return $queries;
        } else {
            return $base;
        }
    }

    protected function genURI(string $endpoint, ?array $queries = null) : Uri {
        $uri = new Uri($this->joinURL($endpoint));
        if (is_array($queries)) {
            $uri = Uri::withQueryValues($uri, $queries);
        }
        return $uri;
    }

    protected function callGet(string $endpoint, ?array $queries = null, ?string $accessToken = null) : \stdClass {
        $headers = [];
        if (!is_null($accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }
        $request = new Request(
            'GET', $this->genURI($endpoint, $queries),
            $headers
        );
        $response = $this->client->send($request);
        return self::parseResponse($response->getBody()->getContents());
    }

    protected function callGetStream(string $endpoint, array $queries, ?string $accessToken = null) : StreamInterface {
        $headers = [];
        if (!is_null($accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }
        $request = new Request(
            'GET', $this->genURI($endpoint, $queries),
            $headers
        );
        $response = $this->client->send($request);
        return $response->getBody();
    }

    protected function callPost(string $endpoint, $object, ?string $accessToken = null) : \stdClass {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        if (!is_null($accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }
        $body = json_encode($object, JSON_UNESCAPED_UNICODE);
        $request = new Request(
            'POST', $this->joinURL($endpoint),
            $headers,
            $body,
        );
        $response = $this->client->send($request);
        return self::parseResponse($response->getBody()->getContents());
    }

    protected function callPostFormData(string $endpoint, array $form, ?string $accessToken = null) : \stdClass {
        $headers = [
            'Content-Type' => 'multipart/form-data',
        ];
        if (!is_null($accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $accessToken;
        }
        $body = [];
        foreach ($form as $key => $value) {
            if (is_array($value)) {
                list($value, $filename) = $value;
            }
            $part = [
                'name' => $key,
                'contents' => $value,
            ];
            if (isset($filename)) {
                $part['filename'] = $filename;
            }
            $body[] = $part;
        }
        $request = new Request(
            'POST', $this->joinURL($endpoint),
            $headers,
            new MultipartStream($body),
        );
        $response = $this->client->send($request);
        return self::parseResponse($response->getBody()->getContents());
    }

    private static function parseResponse(string $body) : \stdClass {
        $json = json_decode($body);
        if (is_object($json) && property_exists($json, 'code')) {
            $code = $json->code;
            if ($code == 0) {
                return $json;
            }
        } else {
            $code = 0;
        }
        throw new FeishuClientResponseException($code, $body);
    }

    private function fetchAppAccessToken() : object {
        $request = [
            'app_id' => $this->config->appId,
            'app_secret' => $this->config->appSecret,
        ];
        return $this->callPost(Endpoints::GET_APP_ACCESS_TOKEN, $request);
    }

    private function fetchTenantAccessToken() : object {
        $request = [
            'app_id' => $this->config->appId,
            'app_secret' => $this->config->appSecret,
        ];
        return $this->callPost(Endpoints::GET_TENANT_ACCESS_TOKEN, $request);
    }

    public function getAppAccessToken() : string {
        $accessToken = $this->tokenStorage->getAppAccessToken();
        if (is_null($accessToken)) {
            $result = $this->fetchAppAccessToken();
            $accessToken = $result->app_access_token;
            $expire = $result->expire;
            $this->tokenStorage->setAppAccessToken($accessToken, $expire);
        }
        return $accessToken;
    }

    public function getTenantAccessToken() : string {
        $accessToken = $this->tokenStorage->getTenantAccessToken();
        if (is_null($accessToken)) {
            $result = $this->fetchTenantAccessToken();
            $accessToken = $result->tenant_access_token;
            $expire = $result->expire;
            $this->tokenStorage->setTenantAccessToken($accessToken, $expire);
        }
        return $accessToken;
    }
}
