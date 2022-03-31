<?php

namespace Timedoor\TmdMidtransIris\Exception;

use Exception;

class UnauthorizedRequestException extends Exception
{
    public function __construct(?string $message = "")
    {
        parent::__construct(
            is_null($message) ? 'Please check your API or Merchant key' : $message,
            401,
            null
        );
    }
}