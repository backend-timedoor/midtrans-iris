<?php

namespace Timedoor\TmdMidtransIris\Dto;

use JsonSerializable;

final class PayoutRequestCollection implements JsonSerializable
{
    /**
     * @var PayoutRequest[]
     */
    private $payouts = [];

    /**
     * Define how data should be serialized using json
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'payouts' => array_map(function ($item) {
                return $item->jsonSerialize();
            }, $this->payouts)
        ]; 
    }

    /**
     * Get the value of payouts
     *
     * @return  array
     */ 
    public function getPayouts()
    {
        return $this->payouts;
    }

    /**
     * Set the value of payouts
     *
     * @param  array  $payouts
     *
     * @return  self
     */ 
    public function setPayouts(array $payouts)
    {
        $this->payouts = $payouts;

        return $this;
    }
}