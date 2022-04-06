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
     * The "approver" api key
     *
     * @var string
     */
    public static $approverApiKey;

    /**
     * The "creator" api key
     *
     * @var string
     */
    public static $creatorApiKey;

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
     * @param   string $path
     * @return  string
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
    public static function getAuthorizationKey($actor = Actor::CREATOR)
    {
        $apiKey = static::$creatorApiKey;

        if ($actor == Actor::APPROVER) {
            $apiKey = static::$approverApiKey;
        }

        return base64_encode(sprintf('%s:', $apiKey));
    }
}