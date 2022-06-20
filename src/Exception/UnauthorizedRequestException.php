<?php

namespace Timedoor\TmdMidtransIris\Exception;

use Exception;

class UnauthorizedRequestException extends Exception
{
    protected $errors;

    public function __construct(?string $message = "", $errors = [])
    {
        $this->errors = $errors;

        parent::__construct(
            is_null($message) ? 'Please check your API or Merchant key' : $message,
            401
        );
    }

    /**
     * Get the error details
     *
     * @return string|array
     */
    public function getErrors()
    {
        return $this->errors; 
    }
}