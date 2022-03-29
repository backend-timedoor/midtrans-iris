<?php

namespace Timedoor\TmdMidtransIris;

class Config
{
    /**
     * The merchant key
     *
     * @var string
     */
    public static $merchantKey;

    /**
     * The api key
     *
     * @var string
     */
    public static $apiKey;

    /**
     * Environment should be production or sandbox
     *
     * @var boolean
     */
    public static $isProduction = false;

    /**
     * Midtrans Iris urls
     */
    const SANDBOX_BASE_URL = 'https://app.sandbox.midtrans.com/iris';

    const PRODUCTION_BASE_URL = 'https://app.midtrans.com/iris';

    /**
     * Get api base url based on the environment
     *
     * @return string
     */
    public static function getBaseUrl()
    {
        return static::$isProduction ? static::PRODUCTION_BASE_URL : static::SANDBOX_BASE_URL; 
    }

    /**
     * Build an absolute url with the given path
     *
     * @param string $path
     * @return string
     */
    public static function buildUrl($path)
    {
        return sprintf('%s/%s', static::getBaseUrl(), trim($path, '/')); 
    }

    /**
     * Get encoded authorization key
     *
     * @return string
     */
    public static function getAuthorizationKey()
    {
        return base64_encode(sprintf('%s:', static::$apiKey));
    }
}