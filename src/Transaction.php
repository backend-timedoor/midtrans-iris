<?php

namespace Timedoor\TmdMidtransIris;

use DateTime;
use Timedoor\TmdMidtransIris\Models\Transaction as TransactionModel;
use Timedoor\TmdMidtransIris\Utils\ConvertException;

class Transaction extends BaseService
{
    /**
     * Get transaction histories filtered by date
     * 
     * https://iris-docs.midtrans.com/#transaction-history
     *
     * @param   \DateTime|null $from
     * @param   \DateTime|null $to
     * @throws  \Timedoor\TmdMidtransIris\Exception\UnauthorizedRequestException
     * @return  \Timedoor\TmdMidtransIris\Models\Transaction[]
     */
    public function history(?DateTime $from = null, ?DateTime $to = null)
    {
        $params = [
            'from_date' => !is_null($from) ? $from->format('Y-m-d') : null,
            'to_date'   => !is_null($to) ? $to->format('Y-m-d') : null
        ];

        $response = $this->apiClient->get('/statements', [], $params);
        
        ConvertException::fromResponse($response);

        return array_map(function ($item) {
            return TransactionModel::fromArray($item);
        }, $response->getBody() ?? []);
    }
}