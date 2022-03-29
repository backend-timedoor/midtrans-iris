<?php

namespace Timedoor\TmdMidtransIris\Utils;

use Timedoor\TmdMidtransIris\Api\ApiResponse;
use Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException;

class ConvertException
{
    /**
     * Convert any exception to the desired exception class
     *
     * @param   ApiResponse $resp
     * @throws  Exception
     */
    public static function fromResponse(ApiResponse $resp)
    {
        if ($resp->isError()) {
            switch ($resp->getCode()) {
                case 401:
                    throw new UnauthorizedRequestException;
            }
        }
    }
}