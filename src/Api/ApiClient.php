<?php

namespace Timedoor\TmdMidtransIris\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use JsonSerializable;
use Timedoor\TmdMidtransIris\Actor;
use Timedoor\TmdMidtransIris\Config;
use Timedoor\TmdMidtransIris\Utils\Json;
use Timedoor\TmdMidtransIris\Utils\Str;

class ApiClient implements IApiClient
{
    /**
     * GuzzleHttp Client
     *
     * @var Client
     */
    private $httpClient;

    /**
     * The actor that is going to make the request
     *
     * @var string
     */
    private $actor = Actor::CREATOR;

    /**
     * The Iris Api base path
     */
    private const API_BASE_PATH = '/api/v1';
    
    public function __construct($options = [], $actor = Actor::CREATOR)
    {
        $this->actor = $actor;

        if (is_null($this->httpClient)) {
            $this->httpClient = $this->newHttpClient($options);
        } 
    }

    /**
     * Create a new http client
     *
     * @return void
     */
    public function newHttpClient($options = [])
    {
        return new Client(array_merge($options, [
            'base_uri' => Config::getBaseUrl(),
        ])); 
    }

    /**
     * Make a POST request
     *
     * @param   string            $path
     * @param   JsonSerializable  $body
     * @param   array             $headers
     * @param   array             $query
     * @return  mixed
     */
    public function post($path, JsonSerializable $body, $headers = [], $query = [])
    {
        return self::call('POST', $path, $body, $headers, $query);
    }

    /**
     * Make a PATCH request
     *
     * @param   string            $path
     * @param   JsonSerializable  $body
     * @param   array             $headers
     * @param   array             $query
     * @return  mixed
     */
    public function patch($path, JsonSerializable $body, $headers = [], $query = [])
    {
        return self::call('PATCH', $path, $body, $headers, $query);
    }

    /**
     * Make a GET request
     *
     * @param   string  $path
     * @param   array   $headers
     * @param   array   $query
     * @return  mixed
     */
    public function get($path, $headers = [], $query = [])
    {
        return self::call('GET', $path, null, $headers, $query);
    }

    /**
     * Make an API Call
     *
     * @param   string                  $method
     * @param   string                  $path
     * @param   JsonSerializable|null   $body
     * @param   array                   $headers
     * @param   array                   $query
     * @return  ApiResponse
     */
    public function call($method, $path, ?JsonSerializable $body = null, $headers = [], $query = [])
    {
        $options    = $this->buildHttpConfiguration($method, $body, $headers, $query);
        $uri        = sprintf('iris/%s/%s', trim(static::API_BASE_PATH, '/'), trim($path, '/'));

        try {
            $response = $this->httpClient->request($method, $uri, $options);

            return new ApiResponse(
                $response->getStatusCode(),
                null,
                Json::decode($response->getBody()->getContents())
            );
        } catch (RequestException $e) {
            if ($e instanceof BadResponseException) {
                $response = $e->getResponse();

                return new ApiResponse(
                    $response->getStatusCode(),
                    $response->getReasonPhrase(),
                    Json::decode($response->getBody()->getContents())
                );
            }
            
            $response = $e->getResponse();

            return new ApiResponse(
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                Json::decode($response->getBody()->getContents())
            );
        }
    }

    public function buildHttpConfiguration($method, ?JsonSerializable $body = null, $headers = [], $query = [])
    {
        $headers = array_merge($headers, [
            'Authorization' => sprintf('Basic %s', Config::getAuthorizationKey($this->actor)),
            'Accept'        => 'application/json' 
        ]);

        $options = [
            'headers'   => $headers,
            'query'     => $query
        ];

        if (($method == 'POST' || $method == 'PUT' || $method == 'PATCH') && !is_null($body)) {
            $options['headers'] = array_merge($options['headers'], [
                'Content-Type'      => 'application/json',
                'X-Idempotency-Key' => Str::random(32)
            ]);

            $options['json'] = $body->jsonSerialize();
        }

        return $options;
    }
}