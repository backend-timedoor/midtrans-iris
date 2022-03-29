<?php

namespace Timedoor\TmdMidtransIris\Api;

use JsonSerializable;

interface IApiClient {
    /**
     * Make a POST request
     *
     * @param   string            $path
     * @param   JsonSerializable  $body
     * @param   array             $headers
     * @param   array             $query
     * @return  ApiResponse
     */
    public function post($path, JsonSerializable $body, $headers = [], $query = []);

    /**
     * Make a PATCH request
     *
     * @param   string            $path
     * @param   JsonSerializable  $body
     * @param   array             $headers
     * @param   array             $query
     * @return  ApiResponse
     */
    public function patch($path, JsonSerializable $body, $headers = [], $query = []);

    /**
     * Make a GET request
     *
     * @param   string  $path
     * @param   array   $headers
     * @param   array   $query
     * @return  ApiResponse
     */
    public function get($path, $headers = [], $query = []);
}