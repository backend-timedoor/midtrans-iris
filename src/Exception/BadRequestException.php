<?php

namespace Timedoor\TmdMidtransIris\Exception;

use Exception;

class BadRequestException extends Exception
{
    private $errors = [];

    public function __construct(?int $code = 400, ?string $message = null, $errors = [])
    {
        $this->errors = $errors;

        parent::__construct(
            is_null($message) ? 'Bad Request' : $message,
            $code
        );
    }

    public function getErrors()
    {
        return $this->errors; 
    }
}