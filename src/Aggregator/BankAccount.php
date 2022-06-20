<?php

namespace Timedoor\TmdMidtransIris\Aggregator;

use Timedoor\TmdMidtransIris\BaseService;
use Timedoor\TmdMidtransIris\Utils\ConvertException;
use Timedoor\TmdMidtransIris\Utils\Map;

class BankAccount extends BaseService
{
    /**
     * Get current account balance
     *
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException|\Timedoor\TmdMidtransIris\Exception\BadRequestException
     * @return  int
     */
    public function balance()
    {
        $response = $this->apiClient->get('/balance');

        ConvertException::fromResponse($response);

        $body = new Map($response->getBody());

        return $body->get('balance', 0);
    }
}