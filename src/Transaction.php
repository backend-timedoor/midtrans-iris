<?php

namespace Timedoor\TmdMidtransIris;

use DateTime;
use Timedoor\TmdMidtransIris\Models\Transaction as TransactionModel;
use Timedoor\TmdMidtransIris\Utils\ConvertException;
use Timedoor\TmdMidtransIris\Utils\Map;

class Transaction extends BaseService
{
    /**
     * Get transaction histories filtered by date
     * 
     * https://iris-docs.midtrans.com/#transaction-history
     *
     * @param   \DateTime|null $from
     * @param   \DateTime|null $to
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

        $result = [];

        foreach ($response->getBody() as $item) {
            $item       = new Map($item);
            $result[]   = (new TransactionModel)
                            ->setRefNo($item->get('reference_no'))
                            ->setBeneficiaryName($item->get('beneficiary_name'))
                            ->setBeneficiaryAccount($item->get('beneficiary_account'))
                            ->setAccount($item->get('account'))
                            ->setType($item->get('type'))
                            ->setAmount($item->get('amount'))
                            ->setStatus($item->get('status'))
                            ->setCreatedAt($item->get('created_at'));
        }

        return $result;
    }
}