<?php

namespace Timedoor\TmdMidtransIris\Utils;

use Timedoor\TmdMidtransIris\Api\ApiResponse;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
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
            if ($resp->getCode() == 401) {
                $body       = new Arr($resp->getBody());
                $errorMsg   = $body->get('error_message');

                throw new UnauthorizedRequestException(
                    $errorMsg,
                    $body->get('errors')
                );
            } else if (($resp->getCode() == 400) || ($resp->getCode() == 404)) {
                $body       = new Arr($resp->getBody());
                $errorMsg   = !is_null($body->get('error_message'))
                                ? $body->get('error_message')
                                : $resp->getErrors();

                throw new BadRequestException(
                    $resp->getCode(),
                    $errorMsg,
                    $body->get('errors', [])
                );
            }
        }
    }
}