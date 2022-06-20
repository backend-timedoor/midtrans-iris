<?php

namespace Timedoor\TmdMidtransIris\Dto;

use JsonSerializable;

final class PlainRequest implements JsonSerializable
{
    /**
     * The request body
     *
     * @var array
     */
    private $body;

    public function jsonSerialize()
    {
        return $this->body; 
    }

    /**
     * Get the request body
     *
     * @return  array
     */ 
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the request body
     *
     * @param  array  $body  The request body
     *
     * @return  self
     */ 
    public function setBody(array $body)
    {
        $this->body = $body;

        return $this;
    }
}