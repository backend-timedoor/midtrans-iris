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
            switch ($resp->getCode()) {
                case 401:
                    throw new UnauthorizedRequestException;
                case 400:
                case 404:
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