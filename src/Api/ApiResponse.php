<?php

namespace Timedoor\TmdMidtransIris\Api;

class ApiResponse
{
    /**
     * HTTP status code
     *
     * @var int
     */
    private $code;

    /**
     * Error message
     *
     * @var string
     */
    private $errors;

    /**
     * Response body
     *
     * @var mixed
     */
    private $body;

    public function __construct($code, $errors = null, $body = null)
    {
        $this->code     = $code; 
        $this->errors   = $errors;
        $this->body     = $body;
    }

    public function isError()
    {
        return !is_null($this->errors);
    }

    /**
     * Get the value of code
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */ 
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of errors
     */ 
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set the value of errors
     *
     * @return  self
     */ 
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Get the value of body
     */ 
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the value of body
     *
     * @return  self
     */ 
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}