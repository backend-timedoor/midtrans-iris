<?php

namespace Timedoor\TmdMidtransIris\Utils;

use Exception;
use Timedoor\TmdMidtransIris\Api\ApiResponse;
use Timedoor\TmdMidtransIris\Exception\BadRequestException;
use Timedoor\TmdMidtransIris\Exception\GeneralException;
use Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException;

class ConvertException
{
    /**
     * Convert any exception to the desired exception class
     *
     * @param   \Timedoor\TmdMidtransIris\Api\ApiResponse $resp
     * @throws  \Exception
     */
    public static function fromResponse(ApiResponse $resp)
    {
        if ($resp->isError()) {
            $code = $resp->getCode();

            if ($code == 401) {
                $body       = new Map($resp->getBody());
                $errorMsg   = $body->get('error_message');

                throw new UnauthorizedRequestException(
                    $errorMsg,
                    $body->get('errors')
                );
            } else if (($code == 400) || ($code == 404)) {
                $body       = new Map($resp->getBody());
                $errorMsg   = !is_null($body->get('error_message'))
                                ? $body->get('error_message')
                                : $resp->getErrors();

                throw new BadRequestException(
                    $code,
                    $errorMsg,
                    $body->get('errors', [])
                );
            } else if ($code === 0) {
                throw new GeneralException($resp->getErrors(), $code);
            }
        }
    }
}